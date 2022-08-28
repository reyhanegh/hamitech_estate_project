<?
// ----------- start config methods ------------------------------------------------------------------------------------
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(-1);

session_start();
include('jdf.php');
date_default_timezone_set("Asia/Tehran");
// ----------- end config methods --------------------------------------------------------------------------------------

// ----------- start DB class ------------------------------------------------------------------------------------------
class DB
{

    // ----------- properties
    protected $_DB_HOST = 'localhost';
    protected $_DB_USER = 'root';
    protected $_DB_PASS = '';
    protected $_DB_NAME = 'estatedb';
    protected $connection;

    // ----------- constructor
    public function __construct()
    {
        $this->connection = mysqli_connect($this->_DB_HOST, $this->_DB_USER, $this->_DB_PASS, $this->_DB_NAME);
        if ($this->connection) {
            $this->connection->query("SET NAMES 'utf8'");
            $this->connection->query("SET CHARACTER SET 'utf8'");
            // $this->connection->query("SET character setconnectionection = 'utf8'");
        }
    }

    // ----------- for return connection
    public function connect()
    {
        return $this->connection;
    }

}

// ----------- end DB class --------------------------------------------------------------------------------------------

// ----------- start Action class --------------------------------------------------------------------------------------
class Action
{

    // ----------- properties
    public $connection;

    // ----------- constructor
    public function __construct()
    {
        $db = new DB();
        $this->connection = $db->connect();
    }

    // ----------- start main methods ----------------------------------------------------------------------------------

    // ----------- get current page url
    public function url()
    {
        $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $url = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        return $url;
    }

    // ----------- for check result of query
    public function result($result)
    {
        if (!$result) {
            $errorno = mysqli_errno($this->connection);
            $error = mysqli_error($this->connection);
            echo "Error NO : $errorno";
            echo "<br>";
            echo "Error Message : $error";
            echo "<hr>";
            return false;
        }
        return true;
    }

    // ----------- count of table's field
    public function table_cunter($table)
    {
        $result = $this->connection->query("SELECT * FROM `$table` ");
        if (!$this->result($result)) return false;
        return $result->num_rows;
    }

    // ----------- get all fields in table
    public function table_list($table)
    {
        $id = $this->admin()->id;
        $result = $this->connection->query("SELECT * FROM `$table` ORDER BY `id` DESC");
        if (!$this->result($result)) return false;
        return $result;
    }

    // ----------- change status field
    // public function change_status($table, $id, $status)
    // {
    //     $now = time();
    //     $result = $this->connection->query("UPDATE `$table` SET 
    //     `status`='$status',
    //     `updated_at`='$now'
    //     WHERE `id` ='$id'");
    //     if (!$this->result($result)) return false;
    //     return $id;
    // }

    // ----------- get data from table by id
    public function get_data($table, $id)
    {
        $result = $this->connection->query("SELECT * FROM `$table` WHERE id='$id'");
        if (!$this->result($result)) return false;
        $row = $result->fetch_object();
        return $row;
    }

     // ----------- get id from category name
    //  public function get_id($table, $name)
    //  {
    //      $result = $this->connection->query("SELECT * FROM `$table` WHERE name='$name'");
    //      if (!$this->result($result)) return false;
    //      $row = $result->fetch_object();
    //      return $row;
    //  }
    // ----------- remove data from table
    public function remove_data($table, $id)
    {
        $result = $this->connection->query("DELETE FROM `$table` WHERE id='$id'");
        if (!$this->result($result)) return false;
        return true;
    }

    // ----------- clean strings (to prevent sql injection attacks)
    public function clean($string, $status = true)
    {
        if ($status)
            $string = htmlspecialchars($string);
        $string = stripslashes($string);
        $string = strip_tags($string);
        $string = mysqli_real_escape_string($this->connection, $string);
        return $string;
    }

    // ----------- for clean and get requests
    public function request($name, $status = true)
    {
        return $this->clean($_REQUEST[$name], $status);
    }

    // ----------- for get and convert date
    // public function request_date($name)
    // {
    //     $name = $this->request('birthday', false);
    //     $name = $this->shamsi_to_miladi($name);
    //     return strtotime($name);
    // }

    // ----------- convert timestamp to shamsi date
    public function time_to_shamsi($timestamp)
    {
        return $this->miladi_to_shamsi(date('Y-m-d', $timestamp));
    }

    // ----------- convert shamsi date to miladi date
    public function shamsi_to_miladi($date)
    {
        $pieces = explode("/", $date);
        $day = $pieces[2];
        $month = $pieces[1];
        $year = $pieces[0];
        $b = jalali_to_gregorian($year, $month, $day, $mod = '-');
        $f = $b[0] . '-' . $b[1] . '-' . $b[2];
        return $f;
    }
    // ************ for signup-user-report to_date field
  // ----------- convert shamsi date to miladi date 
  public function shamsi_to_miladi_v2($date)
  {
      $pieces = explode("/", $date);
      $day = $pieces[2]+1;
      $month = $pieces[1];
      $year = $pieces[0];
      $b = jalali_to_gregorian($year, $month, $day, $mod = '-');
      $f = $b[0] . '-' . $b[1] . '-' . $b[2];
      return $f;
  }
 // ----------- convert miladi date to shamsi date
 public function miladi_to_shamsi_v2($date)
 {
     $pieces = explode("-", $date);
     $year = $pieces[0];
     $month = $pieces[1];
     $day = $pieces[2]-1;
     $b = gregorian_to_jalali($year, $month, $day, $mod = '-');
     $f = $b[0] . '/' . $b[1] . '/' . $b[2];
     return $f;
 }
    // ************ for signup-user-report to_date field






    // ----------- convert miladi date to shamsi date
    public function miladi_to_shamsi($date)
    {
        $pieces = explode("-", $date);
        $year = $pieces[0];
        $month = $pieces[1];
        $day = $pieces[2];
        $b = gregorian_to_jalali($year, $month, $day, $mod = '-');
        $f = $b[0] . '/' . $b[1] . '/' . $b[2];
        return $f;
    }

    // ----------- for send sms to mobile number
    public function send_sms($mobile, $textMessage)
    {
        $webServiceURL = "";
        $webServiceSignature = "";
        $webServiceNumber = "";
        $textMessage = mb_convert_encoding($textMessage, "UTF-8");
        $parameters['signature'] = $webServiceSignature;
        $parameters['toMobile'] = $mobile;
        $parameters['smsBody'] = $textMessage;
        $parameters['retStr'] = ""; // return reference send status and mobile and report code for delivery
        try {
            $con = new SoapClient($webServiceURL);
            $responseSTD = (array)$con->Send($parameters);
            $responseSTD['retStr'] = (array)$responseSTD['retStr'];
        } catch (SoapFault $ex) {
            echo $ex->faultstring;
        }
    }

    // ----------- create random token
    public function get_token($length)
    {
        $token = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet .= "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet .= "0123456789";
        $max = strlen($codeAlphabet);
        for ($i = 0; $i < $length; $i++) {
            $token .= $codeAlphabet[rand(0, $max - 1)];
        }
        return $token;
    }

    // ----------- end main methods ------------------------------------------------------------------------------------

    // ----------- start ADMINS ----------------------------------------------------------------------------------------
    // ----------- for login admin
    public function admin_login($user, $pass)
    {
        $result = $this->connection->query("SELECT * FROM `tbl_admin` WHERE `username`='$user' AND `password`='$pass'");
        if (!$this->result($result)) return false;
        $rowcount = mysqli_num_rows($result);
        $row = $result->fetch_object();
        if ($rowcount) {
            $this->admin_update_last_login($row->id);
            $_SESSION['admin_id'] = $row->id;
            // $_SESSION['admin_access'] = $row->access;
            return true;
        }
        return false;
    }

    // ----------- for check access (admin access)
    public function auth()
    {
        // if (isset($_SESSION['admin_id']) && isset($_SESSION['admin_access']))
        if (isset($_SESSION['admin_id']) )
            return true;
        return false;
    }

    // ----------- for check access (guest access)
    public function guest()
    {
        // if (isset($_SESSION['admin_id']) && isset($_SESSION['admin_access']))
        if (isset($_SESSION['admin_id']))
            return false;
        return true;
    }

    // ----------- update last login of admin (logged)
    public function admin_update_last_login($id)
    {
        $now = strtotime(date('Y-m-d H:i:s'));
        $result = $this->connection->query("UPDATE `tbl_admin` SET `last_login`='$now' WHERE `id`='$id'");
        if (!$this->result($result)) return false;
        return true;
    }

    // ----------- update profile (logged admin)
    public function profile_edit($first_name, $last_name, $phone, $password)
    {
        $id = $this->admin()->id;
        $now = time();
        $result = $this->connection->query("UPDATE `tbl_admin` SET 
        `first_name`='$first_name',
        `last_name`='$last_name',
        `phone`='$phone',
        `password`='$password',
        `updated_at`='$now'
        WHERE `id` ='$id'");
        if (!$this->result($result)) return false;
        return $id;
    }

    // ----------- for show all admins
    public function admin_list()
    {
        $id = $this->admin()->id;
        $result = $this->connection->query("SELECT * FROM `tbl_admin` WHERE NOT `id`='$id' ORDER BY `id` DESC");
        if (!$this->result($result)) return false;
        return $result;
    }

    // ----------- for show all posts or comments or rates
    // public function post_list($type="post")
    // {
    //     $result = $this->connection->query("SELECT * FROM `tbl_$type` ORDER BY `id` DESC");
        
    //     if (!$this->result($result)) return false;
    //     return $result;
    // }
    // // ----------- for show all categories
    // public function category_list($type="cat")
    // {
    //     $result = $this->connection->query("SELECT * FROM `tbl_$type`");
        
    //     if (!$this->result($result)) return false;
    //     return $result;
    // }
   
    // public function post_add($title, $description, $author_id, $cat_id)
    // {
    //     // $now = date('Y-m-d');
    //     $author_id = $_SESSION['admin_id'];
    //     $result = $this->connection->query("INSERT INTO `tbl_post`
    //     (`title`, `description`, `author_id`, `cat_id`) 
    //     VALUES
    //     ('$title', '$description', '$author_id', '$cat_id')");
    //     if (!$this->result($result)) return false;
    //     return $this->connection->insert_id;
    // }

    // // ----------- update post table
    // public function post_edit($id, $title, $description, $author_id, $cat_id)
    // {
    //     $now = date('Y-m-d');
    //     $result = $this->connection->query("UPDATE `tbl_post` SET 
    //     `title`='$title',
    //     `description`='$description',
    //     -- `author_id`='$author_id',
    //     `cat_id`='$cat_id',
    //     `modified_at`='$now'
    //     WHERE `id` ='$id'");
    //     if (!$this->result($result)) return false;
    //     return $id;
    // }


    public function category_add($name)
    {
        
        $result = $this->connection->query("INSERT INTO `tbl_cat`
        (`name`) 
        VALUES
        ('$name')");
        if (!$this->result($result)) return false;
        return $this->connection->insert_id;
    }

    // ----------- update category table
    public function category_edit($id, $name)
    {
        $result = $this->connection->query("UPDATE `tbl_cat` SET 
        `name`='$name'
        WHERE `id` ='$id'");
        if (!$this->result($result)) return false;
        return $id;
    }

    // ----------- add an admin
    // public function admin_add($first_name, $last_name, $phone, $username, $password, $status, $access)
    // {
    //     $now = time();
    //     $result = $this->connection->query("INSERT INTO `tbl_admin`
    //     (`first_name`,`last_name`,`phone`,`username`,`password`,`access`,`status`,`created_at`) 
    //     VALUES
    //     ('$first_name','$last_name','$phone','$username','$password','$access','$status','$now')");
    //     if (!$this->result($result)) return false;
    //     return $this->connection->insert_id;
    // }

    // // ----------- update admin's detail
    // public function admin_edit($id, $first_name, $last_name, $phone, $username, $password, $status, $access)
    // {
    //     $now = time();
    //     $result = $this->connection->query("UPDATE `tbl_admin` SET 
    //     `first_name`='$first_name',
    //     `last_name`='$last_name',
    //     `phone`='$phone',
    //     `username`='$username',
    //     `password`='$password',
    //     `access`='$access',
    //     `status`='$status',
    //     `updated_at`='$now'
    //     WHERE `id` ='$id'");
    //     if (!$this->result($result)) return false;
    //     return $id;
    // }

    // ----------- remove admin
    public function admin_remove($id)
    {
        if ($this->admin_get($id)->access) return false;
        return $this->remove_data("tbl_admin", $id);
    }

    // // ----------- remove post or rate or comment
    // public function post_remove($id)
    // {
        
    //     if (!$this->admin_get($_SESSION['admin_id'])->access) return false;
    //     return $this->remove_data("tbl_post", $id);
    // }
    // ----------- remove category
    public function category_remove($id)
    {
        // if ($this->admin_get($id)->access) return false;
        return $this->remove_data("tbl_cat", $id);
    }

    // ----------- change admin's status
    // public function admin_status($id)
    // {
    //     // admin could not ghange another admin
    //     if ($this->admin_get($id)->access) return false;
    //     $status = $this->admin_get($id)->status;
    //     $status = !$status;
    //     return $this->change_status('tbl_admin', $id, $status);
    // }

    // ----------- change comment's status
    // public function comment_status($id)
    // {
    //     if ($this->admin_get($id)->access) return false;
    //     $status = $this->get_data('tbl_comment', $id)->status;
    //     $status = !$status;
    //     return $this->change_status('tbl_comment', $id, $status);
    // }

    // ----------- get admin's data
    public function admin_get($id)
    {
        return $this->get_data("tbl_admin", $id);
    }

    // ----------- get admin's data (logged)
    public function admin()
    {
        $id = $_SESSION['admin_id'];
        return $this->get_data("tbl_admin", $id);
    }

    // ----------- count of admin
    public function admin_counter()
    {
        return $this->table_cunter("tbl_admin");
    }

    // ----------- end ADMINS ------------------------------------------------------------------------------------------

    // ----------- start USERS -----------------------------------------------------------------------------------------

    public function user_list()
    {
        return $this->table_list("tbl_user");
    }

    // public function user_add($first_name, $last_name, $national_code, $phone, $username, $password, $birthday, $status)
    // {
    //     $now = time();
    //     $result = $this->connection->query("INSERT INTO `tbl_user`
    //     (`first_name`,`last_name`,`national_code`,`phone`,`username`,`password`,`birthday`,`status`,`created_at`) 
    //     VALUES
    //     ('$first_name','$last_name','$national_code','$phone','$username','$password','$birthday','$status','$now')");
    //     if (!$this->result($result)) return false;
    //     return $this->connection->insert_id;
    // }

    // public function user_edit($id, $first_name, $last_name, $national_code, $phone, $username, $password, $birthday, $status)
    // {
    //     $now = time();
    //     $result = $this->connection->query("UPDATE `tbl_user` SET 
    //     `first_name`='$first_name',
    //     `last_name`='$last_name',
    //     `national_code`='$national_code',
    //     `phone`='$phone',
    //     `username`='$username',
    //     `password`='$password',
    //     `birthday`='$birthday',
    //     `status`='$status',
    //     `updated_at`='$now'
    //     WHERE `id` ='$id'");
    //     if (!$this->result($result)) return false;
    //     return $id;
    // }

    // public function user_remove($id)
    // {
    //     return $this->remove_data("tbl_user", $id);
    // }

    // public function user_status($id)
    // {
    //     $status = $this->user_get($id)->status;
    //     $status = !$status;
    //     return $this->change_status('tbl_user', $id, $status);
    // }

    // public function user_get($id)
    // {
    //     return $this->get_data("tbl_user", $id);
    // }

    public function user_counter()
    {
        return $this->table_cunter("tbl_user");
    }

    // ----------- end USERS -------------------------------------------------------------------------------------------


}
// ----------- end Action class ----------------------------------------------------------------------------------------


