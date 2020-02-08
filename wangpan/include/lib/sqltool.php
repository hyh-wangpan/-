<?php
$dir = dirname(__FILE__);
require_once $dir."/../../config.php";
class sqltool
{
	private $conn;		//数据库连接
	public $selecttime;		//查询时间（装逼用的功能）
	
	//构造函数，创建一个数据库连接
	public function __construct($dbname=DB_NAME,$dbhost=DB_HOST,$dbuser=DB_USER,$dbpassword=DB_PASSWORD,$dbport=DB_PORT)
	{
		$starttime=microtime(1);
		$this->conn=new mysqli($dbhost,$dbuser,$dbpassword,$dbname,$dbport);
		if($this->conn->connect_error)
		{
			die('Connect Error('.$this->conn->connect_errno.')'.$this->conn->connect_error);
		}
		$this->selecttime=microtime(1)-$starttime;
		$this->setchar(DB_CHARSET);//默认设置utf-8
	}
	
	//设置数据库的字符编码
	public function setchar($char)
    {
		$starttime=microtime(1);
        if (!$this->conn->set_charset($char)) 
		{
		die('Error loading character set $char:'.$this->conn->error);
		}
		$this->selecttime=microtime(1)-$starttime;
    }
	
	//传送一个查询语句，返回查询的结果集
	public function executedql($sql)
	{
		$starttime=microtime(1);
		$res=$this->conn->query($sql) or die($this->conn->error);
		$this->selecttime=microtime(1)-$starttime;
		return $res;
	}
	
	//传送一个数据操纵语句，返回被修改的条数，-1为失败
	public function executedml($sql)
	{
		$starttime=microtime(1);
		$row=$this->conn->query($sql) or die($this->conn->error);
		$this->selecttime=microtime(1)-$starttime;
		if($row)
		{
			return $this->conn->affected_rows;
		}
		else
		{
			return -1;
		}		
		
	}
	
	//查询数据库输入表名，列名，函数等，返回一个结果数组
	public function select($table, $where = '1', $field = "*", $fun = '')
    {
		$starttime=microtime(1);
        $rarr = array();
        if (empty($fun)) 
		{
            $sqlStr = "SELECT $field FROM $table WHERE $where";
            $rt = $this->conn->query($sqlStr) or die($this->conn->error);
            while ($rt && $arr = $rt->fetch_assoc()) 
			{
                array_push($rarr, $arr);
            }
        } 
		else 
		{
            $sqlStr = "SELECT $fun($field) AS rt FROM $table WHERE $where";
            $rt = $this->conn->query($sqlStr) or die($this->conn->error);
            if ($rt) 
			{
                $arr = $rt->fetch_assoc();
                $rarr = $arr['rt'];
            }
			else 
			{
                $rarr = '';
            }
        }
		$this->selecttime=microtime(1)-$starttime;
		$rt->free();
        return $rarr;
	}
	
	//更新数据库，输入表名更新的值，返回修改的条数，-1为失败
	public function update($table, $where, $data)
    {
		$starttime=microtime(1);
        $ddata = '';
        if (is_array($data))
			{
            while (list($k, $v) = each($data))
				{
					if (empty($ddata))
					{
						$ddata = "$k='$v'";
					}
					else 
					{
						$ddata .= ",$k='$v'";
					}
				}
        } 
		else 
		{
            $ddata = $data;
        }
        $sqlStr = "UPDATE $table SET $ddata WHERE $where";
        $row=$this->conn->query($sqlStr) or die($this->conn->error);
		$this->selecttime=microtime(1)-$starttime;
		if($row)
		{
			return $this->conn->affected_rows;
		}
		else
		{
			return -1;
		}		
    }
	
	//插入一条记录，输入表名，要插入的数据，返回插入记录的id，-1为失败
	public function insert($table,$data)
    {
		$starttime=microtime(1);
        $field = '';
        $idata = '';
        if (is_array($data) && array_keys($data) != range(0, count($data) - 1))
		{
            //关联数组
			while (list($k, $v) = each($data))
			{
                if (empty($field))
				{
					$field = "$k";
					$idata = "'$v'";
				} 
				else
				{
					$field .= ",$k";
					$idata .= ",'$v'";
				}
			}
			$sqlStr = "INSERT INTO $table($field) VALUES ($idata)";
		}
		else
		{
            //非关联数组 或字符串
            if (is_array($data)) 
			{
                while (list($k, $v) = each($data)) 
				{
                    if (empty($idata)) 
					{
                        $idata = "'$v'";
                    } 
					else 
					{
                        $idata .= ",'$v'";
                    }
                }

            } 
			else 
			{
                //为字符串
                $idata = $data;
            }
            $sqlStr = "INSERT INTO $table VALUES ($idata)";
        }
		$row=$this->conn->query($sqlStr) or die($this->conn->error);
		$this->selecttime=microtime(1)-$starttime;
        if($row)
        {
            return $this->conn->insert_id;
        }
		else
		{
			return -1;
		}
    }
	
	//删除记录，输入表名，要删除的记录条件，返回删除的条数，-1为失败
    public function delete($table, $where)
    {
		$starttime=microtime(1);
        $sqlStr = "DELETE FROM $table WHERE $where";
		$row=$this->conn->query($sqlStr) or die($this->conn->error);
		$this->selecttime=microtime(1)-$starttime;
        if($row)
		{
			return $this->conn->affected_rows;
		}
		else
		{
			return -1;
		}		
    }
	
	//查询数据库现有表，返回一个数组
	public function showtables()
	{
		$starttime=microtime(1);
		$rarr = array();
		$sqlStr = "SHOW TABLES";
		$rt = $this->conn->query($sqlStr) or die($this->conn->error);
        while ($rt && $arr = $rt->fetch_assoc())
		{
            array_push($rarr, $arr);
        }
		$this->selecttime=microtime(1)-$starttime;
		$rt->free();
        return $rarr;
	}

	//开始一个事务
	public function begin()
	{
		$this->conn->autocommit(FALSE);
	}
	
	//提交一个事务
	public function commit()
	{
		return $this->conn->commit();
	}
	
	//回退当前事务
	public function rollback()
	{
		return $this->conn->rollback();
	}
	
	//关闭当前连接
    public function close()
    {
		return $this->conn->close();
    }
}