<?php
/**
 *
 * @package    PACMEC
 * @category   DB
 * @copyright  2020-2021 Manager Technology CO & FelipheGomez CO
 * @author     FelipheGomez <feliphegomez@gmail.com>
 * @license    license.txt
 * @version    0.0.1
 */
namespace PACMEC\System;
Class DB {
  private $driver, $adapter, $host, $port, $user, $pass, $database, $charset, $prefix;
  private $tables = [];
  private $models = [];
  private $views = [];

  public function __construct() {
		$this->driver           = DB_driver;
		$this->port             = DB_port;
		$this->host             = DB_host;
		$this->user             = DB_user;
		$this->pass             = DB_pass;
		$this->database         = DB_database;
		$this->charset          = DB_charset;
		$this->prefix           = DB_prefix;
    $this->adapter          = $this->conn();
    $this->load_models();
		return $this;
  }

  public function get_tables_info() : Array
  {
    return $this->tables;
  }

  public function get_table_info($table)
  {
    if(isset($this->tables[$table])) return $this->tables[$table];
    return null;
  }

  public function get_model_table($table)
  {
    if(isset($this->tables[$table]) && isset($this->tables[$table]->model)) return $this->tables[$table]->model;
    return null;
  }

  public function get_rule_table($table)
  {
    if(isset($this->tables[$table]) && isset($this->tables[$table]->rules)) return $this->tables[$table]->rules;
  }

  public function get_labels_table($table)
  {
    if(isset($this->tables[$table]) && isset($this->tables[$table]->labels)) return $this->tables[$table]->labels;
  }

  public function load_models() : void
  {
    $sql = "SELECT * from `INFORMATION_SCHEMA`.`TABLES` where (`information_schema`.`TABLES`.`TABLE_SCHEMA` = database())";
    $database_info = Self::FetchAllObject($sql, []);
    if($database_info==false||!\is_array($database_info)||\count($database_info)<=0) throw new \Exception('No se a creado la base de datos o sus tablas.', 1);
    foreach ($database_info as $a) {
      $is_pacmec_tbl = @explode(DB_prefix, $a->TABLE_NAME);
      if(isset($is_pacmec_tbl[1])){
        switch ($a->TABLE_TYPE) {
          case 'VIEW':
          case 'BASE TABLE':
            $this->add_models($a);
            break;
          default:
            break;
        }

      }
    }
  }

  private function add_models(Object $model) : void
  {
    $name = @str_replace(DB_prefix, '', $model->TABLE_NAME);
    $tmp = $this->load_columns($model->TABLE_NAME);
    $item = (Object) [
      "name" => $model->TABLE_NAME,
      "collation" => $model->TABLE_COLLATION,
      "auto_increment" => $model->AUTO_INCREMENT,
      "rows" => $model->TABLE_ROWS,
      "columns" => $tmp->columns,
      "model" => $tmp->model,
      "rules" => $tmp->rules,
      "labels" => $tmp->labels,
      //"data" => $tmp
    ];
    switch ($model->TABLE_TYPE) {
      case 'BASE TABLE':
        $this->tables[$name] = $item;
        break;
      case 'VIEW':
        $this->views[$name] = $item;
        break;
      default:
        break;
    }
  }

  /**
   * @param string $columna
   * @return object ModeloBase
   */
  private function def_column($columna){
    $column = new \stdClass();
    if(!\is_object($columna)){ return $column; }
    $column->name = isset($columna->columna_nombre) ? $columna->columna_nombre : 'no_detect';
    $column->nullValid = (isset($columna->nullValido) && $columna->nullValido == 'YES') ? true : false;
    $column->value_default = $columna->columna_value_default;
    $column->type = $columna->data_tipo;
    $column->key = \array_filter(explode(',', $columna->columna_key));
    $column->extra = \array_filter(explode(',', $columna->columna_extra));
    $column->tbl_ref = $columna->tabla_referencia;
    $column->tbl_column = $columna->columna_referencia;
    $column->auto_increment = (array_search('auto_increment', $column->extra) !== false) ? true : false;
    $column->unique = (array_search('UNI', $column->key) !== false) ? true : false;
    $column->primary = (array_search('PRI', $column->key) !== false) ? true : false;
    $column->mult = (array_search('MUL', $column->key) !== false) ? true : false;
    $column->length_max = isset($columna->length_max) ? (int) $columna->length_max : false;
    $column->value_default = isset($column->value_default) ? $column->value_default : null;
    $column->nullValid = isset($column->nullValid) ? $column->nullValid : true;
    $column->required = ($column->auto_increment == true) ? false : true;
    $column->required = ($column->nullValid == true) ? false : true;
    $column->value = ($column->nullValid == true) ? $column->value_default : $this->get_value_default_sql($column->type, $column->value_default);
    $column->value = (strip_tags($column->value) == strip_tags("CURRENT_TIMESTAMP")) ? date("Y-m-d H:i:s") : $column->value;
    return $column;
  }

	private function load_columns(String $tbl)
  {
    try {
      $_table = @explode(DB_prefix, $tbl);
      $table = $_table[1];
      $db = DB_database;
      $model   = (Object) ["model"=>(object)[]];
      $columns = [];
			$sql = "SELECT
          `tbl_columns`.`ORDINAL_POSITION` AS `posicion_original`,
          `tbl_columns`.`COLUMN_NAME` AS `columna_nombre`,
          `tbl_columns`.`IS_NULLABLE` AS `nullValido`,
          `tbl_columns`.`COLUMN_DEFAULT` AS `columna_value_default`,
          `tbl_columns`.`DATA_TYPE` AS `data_tipo`,
          `tbl_columns`.`COLUMN_TYPE` AS `columna_tipo`,
          `tbl_columns`.`CHARACTER_MAXIMUM_LENGTH` AS `length_max`,
          `tbl_columns`.`COLUMN_KEY` AS `columna_key`,
          `tbl_columns`.`EXTRA` AS `columna_extra`,
          `tbl_columns`.`COLUMN_COMMENT` AS `columna_comnetario`,
          `tbl_rship`.`REFERENCED_TABLE_NAME` AS `tabla_referencia`,
          `tbl_rship`.`REFERENCED_COLUMN_NAME` AS `columna_referencia`
      FROM `information_schema`.`columns` AS `tbl_columns`
      LEFT JOIN `information_schema`.`KEY_COLUMN_USAGE` AS `tbl_rship`
      ON `tbl_rship`.`CONSTRAINT_SCHEMA` IN (?) AND `tbl_columns`.`COLUMN_NAME` = `tbl_rship`.`COLUMN_NAME` AND `tbl_columns`.`table_name` = `tbl_rship`.`table_name` AND `tbl_rship`.`REFERENCED_TABLE_SCHEMA` IS NOT NULL
			WHERE `tbl_columns`.`table_schema` IN (?) AND `tbl_columns`.`table_name` IN (?) ORDER BY `tbl_columns`.`ORDINAL_POSITION` ASC";
      $sending = [$db, $db, $tbl];
      $result = $this->FetchAllObject($sql, $sending);
			if($result !== null && count($result) > 0){
				foreach ($result as $column) {
					$column = $this->def_column($column);
					$columns[] = $column;
					$model->model->{$column->name} = $column->value;

					$inArray = \array_search('UNI', $column->key);
					if(isset($model->isUnique) && $model->isUnique == null && $inArray !== false){ $model->isUnique = true; }
					// Create RULE
					$rule = [];
					$rule["name"] = $column->name;
					$rule["required"] = $column->required;
					$rule["unique"] = $column->unique;
					$rule["nullValid"] = $column->nullValid;
					$rule["auto_increment"] = $column->auto_increment;
					if($column->length_max !== false && $column->length_max > 0){ $rule["length_max"] = $column->length_max; }
					// CREATE LABELS
          $model->columns[] = $column->name;
					$model->rules[$column->name] = $rule;
					$model->labels[$column->name] = ("l_{$table}_{$column->name}");
				}
        return $model;
			} else {
				throw new \Exception("No se cargaron las columnas del modelo ".@get_class($this)."\n");
			}
    }
    catch(\Exception $e){
       // echo $e->getMessage();
       exit();
    }
	}

  public function get_value_default_sql($type="varchar", $default=null)
  {
    switch ($type) {
        case 'varchar':
            return strip_tags($default);
            break;
        case 'text':
            return is_string($default) ? strip_tags($default) : $default;
            break;
        case 'int':
            return (int) $default;
            break;
        case 'datetime':
            return (string) $default;
            break;
        default:
            return is_string($default) ? strip_tags($default) : $default;
            break;
    }
  }

  public function __wakeup()
  {
    $this->getAdapter();
  }

  public function __sleep()
  {
    return array('driver', 'port', 'host', 'user', 'database', 'charset', 'prefix');
  }

	public function getPrefix()
  {
		return $this->prefix;
	}

  public function getTableName(String $tbl_gbl)
  {
    if(isset($this->tables[$tbl_gbl])) return $this->tables[$tbl_gbl]->name;
    return false;
  }

  public function getViewName(String $tbl_gbl)
  {
    if(isset($this->views[$tbl_gbl])) return $this->views[$tbl_gbl]->name;
    return false;
  }

  public function getTableBy(String $tbl_gbl, $by)
  {
    if(isset($this->tables[$tbl_gbl]) && isset($this->tables[$tbl_gbl]->{$by})) return $this->tables[$tbl_gbl]->{$by};
    return false;
  }

  public function getTotalRows() : Array
  {
    $r = [];
    foreach ($this->tables as $tbl => $data) {
      $r[$tbl] = $data->rows;
    }
    return $r;
  }

	public function getAdapter()
  {
		return $this->adapter;
	}

  public static function conexion()
  {
    $n = new Self();
    return $n;
  }

  public function conn()
  {
    try {
      if($this->driver == "mysql" || $this->driver == null){
        $link = new \PDO($this->driver.":host={$this->host};port={$this->port};dbname={$this->database};charset={$this->charset}","{$this->user}","{$this->pass}",[
          \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES {$this->charset}"
        ]);
        $link->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $link->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
      }
      return $link;
    } catch (\PDOException $e) {
      print "Error!: " . $e->getMessage() . "<br/>";
      die();
    }
  }

  public function FetchObject(string $sql, array $params = [])
  {
    try {
      $query = $this->getAdapter()->prepare($sql);
      $result = $query->execute($params);
  		preg_match('/insert+[\s\g\w]+/i', $sql, $is_insert, PREG_OFFSET_CAPTURE);
  		if(isset($is_insert[0])){
  			return (int) $this->getAdapter()->lastInsertId();
  		} else {
  			preg_match('/select+[\s\g\w]+/i', $sql, $is_select, PREG_OFFSET_CAPTURE);
  			if(isset($is_select[0])){
  				return $query->fetch(\PDO::FETCH_OBJ);
  			} else {
  				$result = $query->execute($params);
  				return ($result == true) ? true : false;
  			}
  		}
    }
    catch(\Exception $e){
      // echo $e->getMessage();
      return false;
    }
  }

  public function FetchAllObject($sql, $params = [])
  {
    try {
      $query = $this->getAdapter()->prepare($sql);
      $result = $query->execute($params);
  		preg_match('/select+[\s\g\w]+/i', $sql, $is_select, PREG_OFFSET_CAPTURE);
  		if(isset($is_select[0])){
  			return $query->fetchAll(\PDO::FETCH_OBJ);
  		} else {
  			$result = $query->execute($params);
  			return ($result == true) ? true : false;
  		}
    }
    catch(\Exception $e){
      return false;
    }
  }
}
