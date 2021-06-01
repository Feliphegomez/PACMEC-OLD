<?php
/**
 *
 * @package    PACMEC
 * @category   System
 * @copyright  2020-2021 FelipheGomez & FelipheGomez CO
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

  public function get_rules()
  {
    return $this->rules;
  }

  public function get_columns($include_id=false)
  {
    return $this->getColumns($include_id);
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
		 $r = [];
		 foreach($this->columns as $a){
			 if($a == 'id' && $include_id == false){
			 }else{
				 $r[] = $a;
			 }
		 }
     return $r;
	}


	private function loadColumns()
  {
    try {
      global $PACMEC;
      $result = null;
      // get_tables_info();
      $table = get_called_class()::TABLE_NAME;
      $result = $PACMEC['DB']->get_table_info($table);
      // if(isset($PACMEC['DB']->get_tables_info()[$table]) && isset($PACMEC['DB']->get_tables_info()[$table]->model)) $result = $PACMEC['DB']->get_tables_info()[$table]->model;

      if($result !== null){
        $this->columns       = $result->columns;
        $this->rules         = $result->rules;
        $this->labels        = $result->labels;

        foreach ($result->model as $k => $v) {
          $this->{$k} = $v;
        }
      } else {
        throw new \Exception("No se cargaron las columnas del modelo. {$table}\n");
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

  public static function get_all_pagination($page, $limit=25)
  {
    try {
      $offset        = ($page-1)*$limit;
      $class = get_called_class();
      $r = [];
      $sql = "SELECT * FROM `".$class::get_table()."` ORDER BY `created` ASC LIMIT ? OFFSET ?";
			$result = $class::link()->FetchAllObject($sql, [$limit, $offset]);
      if($result == false) return [];
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
