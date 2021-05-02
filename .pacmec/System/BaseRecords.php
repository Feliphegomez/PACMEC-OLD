<?php
/**
 *
 * @package    PACMEC
 * @category   System
 * @copyright  2020-2021 Manager Technology CO & FelipheGomez CO
 * @author     FelipheGomez <feliphegomez@gmail.com>
 * @license    license.txt
 * @version    0.0.1
 */
namespace PACMEC\System;

class BaseRecords
{
  const TABLE_NAME       = 'undefined';
  const COLUMNS_AUTO_T   = [];
  private $columns       = [];
  private $rules         = [];
  private $labels        = [];

  public function __construct()
  {
    try {
      //$class = get_called_class();
      if(Self::link()==null){ throw new \Exception("Conexion DB no encontrada."); }
      $this->loadColumns();
    } catch (\Exception $e) {
      echo $e->getMessage();
      return $e;
    }
  }

  public function __toString() : String
  {
    $labels_globals = ['name', 'title', 'username', 'id']; // , 'ref', 'sku'
    foreach ($labels_globals as $key) {
      if(property_exists($this, $key)){
        return $this->{$key};
      }
    }

    return "id ".get_called_class()::TABLE_NAME.": {$this->id}";
  }

  public function get_columns()
  {
    return $this->columns;
  }

  public static function link()
  {
    return $GLOBALS['PACMEC']['DB'];
  }

  public static function get_table() : String
  {
    // Self::link()->getTableName(get_called_class()::TABLE_NAME);
    return Self::link()->getTableName(get_called_class()::TABLE_NAME);
  }

  public static function get_table_users_in() : String
  {
    return Self::link()->getTableName("users_".get_called_class()::TABLE_NAME);
  }

  public static function get_table_orders_in() : String
  {
    return Self::link()->getTableName("orders_".get_called_class()::TABLE_NAME);
  }

	public function getColumns($include_id=false)
  {
		 $items = [];
		 foreach($this->columns as $item){
			 if($item->name == 'id' && $include_id == false){

			 }else{
				 $items[] = $item->name;
			 }
		 }
		 return $items;
	}

  public static function get_value_default_sql($type="varchar", $default=null)
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

  /**
   * @param string $columna
   * @return object ModeloBase
   */
  private function modelInitial($columna)
  {
    $column = new \stdClass();
    if(!is_object($columna)){ return $column; }
    $column->name = isset($columna->columna_nombre) ? $columna->columna_nombre : 'no_detect';
    $column->nullValid = (isset($columna->nullValido) && $columna->nullValido == 'YES') ? true : false;
    $column->value_default = $columna->columna_value_default;
    $column->type = $columna->data_tipo;
    $column->key = array_filter(explode(',', $columna->columna_key));
    $column->extra = array_filter(explode(',', $columna->columna_extra));
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
    $column->value = ($column->nullValid == true) ? $column->value_default : Self::get_value_default_sql($column->type, $column->value_default);
    $column->value = (strip_tags($column->value) == strip_tags("CURRENT_TIMESTAMP")) ? date("Y-m-d H:i:s") : $column->value;
    return $column;
  }

	private function loadColumns()
  {
    try {
			$db = DB_database;
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
			$result = Self::link()->FetchAllObject($sql, [$db, $db, Self::get_table()]);
      if($result !== null && count($result) > 0){
				foreach ($result as $column) {
					$column = $this->modelInitial($column);
					$this->columns[] = $column;
					$this->{$column->name} = $column->value;
					$inArray = array_search('UNI', $column->key);
					if(isset($this->isUnique) && $this->isUnique == null && $inArray !== false){ $this->isUnique = true; }
					$rule = [];
					$rule["name"] = $column->name;
					$rule["required"] = $column->required;
					$rule["unique"] = $column->unique;
					if($column->length_max !== false && $column->length_max > 0){ $rule["length_max"] = $column->length_max; }
					$this->rules[$column->name] = $rule;
					// CREATE LABELS
					$this->labels[$column->name] = $column->value;
				}
			} else {
        throw new \Exception("No se cargaron las columnas del modelo.\n");
			}
    }
    catch(\Exception $e){
      echo $e->getMessage()."\n";
      exit();
    }
	}

  public static function get_all()
  {
    try {
      $class = get_called_class();
      $r = [];
      $sql = "Select * from `".$class::get_table()."` ";
			$result = $class::link()->FetchAllObject($sql, []);
      foreach ($result as $item) { $r[] = new $class($item); }
      return $r;
    } catch (\Exception $e) {
      echo $e->getMessage();
      return [];
    }
  }

  public static function get_all_by($k, $v)
  {
    try {
      $class = get_called_class();
      $r = [];
      $sql = "Select * from `".$class::get_table()."` WHERE `{$k}`=?";
			$result = Self::link()->FetchAllObject($sql, [$v]);
      if($result !== false){ foreach ($result as $item) { $r[] = new $class($item); } }
      return $r;
    } catch (\Exception $e) {
      echo $e->getMessage();
      return [];
    }
  }

  public static function get_all_by_order_id($order_id=null) : Array
  {
    try {
      $class = get_called_class();
      if($order_id == null) $order_id = 0;
      $r = [];
      $sql = "Select * from `".$class::get_table_orders_in()."` WHERE `order_id`=?";
			$result = $class::link()->FetchAllObject($sql, [$order_id]);
      if($result !== false){
        return $result;
      }
      return $r;
    } catch (\Exception $e) {
      echo $e->getMessage();
      return [];
    }
  }

  public static function get_all_by_user_id($user_id=null) : Array
  {
    try {
      $class = get_called_class();
      if($user_id == null) $user_id = \userID();
      $r = [];
      $sql = "Select * from `".$class::get_table_users_in()."` WHERE `user_id`=?";
			$result = $class::link()->FetchAllObject($sql, [$user_id]);
      if($result !== false){
        foreach ($result as $item) {
          $a = new $class($item);
          $r[] = $a;
        }
      }
      return $r;
    } catch (\Exception $e) {
      echo $e->getMessage();
      return [];
    }
  }
  // -- STATIC END --//
	public function get_by($k, $v)
  {
    try {
      $sql = "Select * from `".Self::get_table()."` WHERE `{$k}`=?";
			$result = Self::link()->FetchObject($sql, [$v]);
      if($result !== false){ $this->set_all($result); }
      return $result;
    } catch (\Exception $e) {
      echo $e->getMessage();
      return [];
    }
	}

	public function get_by_id($id)
  {
    try {
      $sql = "Select * from `".Self::get_table()."` WHERE `id`=?";
			$result = Self::link()->FetchObject($sql, [$id]);
      if($result !== false){ $this->set_all($result); }
      return $result;
    } catch (\Exception $e) {
      echo $e->getMessage();
      return [];
    }
	}

  public function set_all($obj)
  {
    $obj = (object) $obj;
		foreach(array_keys($this->labels) as $label){
			if(isset($obj->{$label})){
				$this->{$label} = $obj->{$label};
			}
		}
    foreach (get_called_class()::COLUMNS_AUTO_T as $key => $atts) {
      $parts = [];
      if(property_exists($this, $key)){
        foreach ($atts["parts"] as $x) {
          if (property_exists($this, $x)) $x = $this->{$x};
          elseif (isset(${$x})) $x = ${$x};
          elseif (isset($$x)) $x = $$x;
          $parts[] = $x;
        }
        $s = ($atts["autoT"] == true) ? __a(implode($atts["s"], $parts)) : implode($atts["s"], $parts);;
        $this->{$key} = $s;
      }
    }
  }

  public function isValid() : bool
  {
    return (isset($this->id) && $this->id > 0);
  }

  public function getId()
  {
    return isset($this->id) ? $this->id : 0;
  }

}
