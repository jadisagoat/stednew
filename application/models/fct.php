<?php
class Fct extends CI_Model
{

    public function count_cond($table, $cond)
    {
        // Set the condition to filter out deleted records
        $this->db->where('deleted', 0);

        // Apply additional conditions
        $this->db->where($cond);

        // Return the results as an array of rows
        return $this->db->count_all_results($table);
        // return $query->result_array();
    }

    public function search_count_cond($wehereclause)
    {
        $query = $this->db->query($wehereclause);

        // Check if the query was executed successfully
        if ($query) {
            // Return the count of rows
            return $query->num_rows();
        } else {
            // Handle the case where the query fails
            return 0;
        }
    }



    public function updateallviews($pages = 'ayman')
    {
        // gets the user IP Address
        $user_ip = $_SERVER['REMOTE_ADDR'];
        /* $query="select userip from pageview where page='homepage' and userip='$user_ip'";
        $books=$this->fct->search($query);
        if(count($books)>=1)
        {
        }
        else
        { */
        /////////insert
        $_datainfo["page"] = $pages;
        $_datainfo["userip"] = $user_ip;
        $_datainfo["created_date"] = date("Y-m-d h:i:s");
        $this->db->insert("pageview", $_datainfo);
        ////////update
        $cond = array('page' => $pages);
        $d_totalvisit = $this->fct->getonerow("totalview", $cond);
        $allview = $d_totalvisit['totalvisit'];
        // echo  $this->db->last_query();
        $_datainfoup1["totalvisit"] = $allview + 1;
        if ($allview == 0) {
            $_datainfoup1["page"] = $pages;
            $this->db->insert("totalview", $_datainfoup1);
        } else {
            $this->db->where("page", $pages);
            $this->db->update("totalview", $_datainfoup1);
        }
        //}
        $numser = $allview;
        $dataviews = "This page is viewed " . $numser . " times.";
        ////////////////////////////////////////
        return $dataviews;
    }
    function write_file($path, $data)
    {
        if (! write_file($path, $data)) {
            echo 'Unable to write the file';
        } else {
            //echo 'File written!';
        }
    }

    function getonecell($table, $select, $cond)
    {
        $this->db->where($cond);
        $query = $this->db->get($table);
        $res = $query->row_array();
        if (count($res) > 0)
            return $res[$select];
    }

    // Performance: same result as getonecell(), but each (table, column, condition)
    // is read only once per request. Use it in read-only listing loops (N+1 queries).
    function getonecell_cached($table, $select, $cond)
    {
        static $cache = array();
        $key = $table . '|' . $select . '|' . serialize($cond);
        if (!array_key_exists($key, $cache)) {
            $this->db->where($cond);
            $this->db->limit(1);
            $res = $this->db->get($table)->row_array();
            $cache[$key] = ($res && array_key_exists($select, $res)) ? $res[$select] : NULL;
        }
        return $cache[$key];
    }

    function getonerow($table, $condition)
    {
        $this->db->where($condition);
        $query = $this->db->get($table);
        return $query->row_array();
    }

    function searchonerow($wehereclause)
    {
        $query = $this->db->query($wehereclause);
        //echo $this->db->last_query();
        return $query->row_array();
    }



    function getonerecord($table, $condition)
    {
        $this->db->where('deleted', 0);
        $this->db->where($condition);
        $query = $this->db->get($table);
        return $query->row_array();
    }

    function getAll_1($table, $order)
    {
        //$this->db->where('deleted',0);
        $this->db->order_by($order);
        $query = $this->db->get($table);
        return $query->result_array();
    }
    function getAll($table, $order)
    {
        $this->db->where('deleted', 0);
        $this->db->order_by($order);
        $query = $this->db->get($table);
        return $query->result_array();
    }

    function getAll_cond($table, $order, $cond)
    {
        $this->db->where('deleted', 0);
        $this->db->where($cond);
        $this->db->order_by($order);
        $query = $this->db->get($table);
        /*echo $this->db->last_query();*/
        return $query->result_array();
    }

    function getAll_cond_search($table, $order, $cond, $cond2, $cond3)
    {
        $this->db->where('deleted', 0);
        $this->db->where($cond);
        $this->db->or_where($cond2);
        $this->db->where($cond3);
        $this->db->order_by($order);
        $query = $this->db->get($table);
        //echo $this->db->last_query();
        return $query->result_array();
    }
    function search($wehereclause)
    {
        $query = $this->db->query($wehereclause);
        //echo $this->db->last_query();
        return $query->result_array();
    }

    function getAll_limit($table, $order, $des, $limit, $cond = '')
    {
        $this->db->where('deleted', 0);
        $this->db->order_by($order, $des);
        if ($cond)
            $this->db->where($cond);
        $query = $this->db->get($table, $limit);
        return $query->result_array();
    }

    function getAll_001($table, $order)
    {
        $this->db->order_by($order);
        $query = $this->db->get($table);
        return $query->result_array();
    }


    function getto_do_list()
    {
        $yesterday = date("Y-m-d", time() - 86400);
        $q = "SELECT * FROM `do_it` WHERE Deadline > '" . $yesterday . "' AND deleted = 0 ORDER BY  Deadline LIMIT 4";
        $query = $this->db->query($q);
        return $query->result_array();
    }
    function getto_do_list_completed()
    {
        $yesterday = date("Y-m-d", time() - 86400);
        $q = "SELECT * FROM `do_it` WHERE  deleted = 0  AND completed = 1 ORDER BY  Deadline LIMIT 4";
        $query = $this->db->query($q);
        return $query->result_array();
    }

    function module($id)
    {
        $this->db->where('id_module', $id);
        $query = $this->db->get('module');
        $res = $query->row_array();
        return $res["activate"];
    }

    function getAll_orderdate($table)
    {
        $this->db->where('deleted', 0);
        $this->db->order_by('created_date', 'DESC');
        $query = $this->db->get($table);
        return $query->result_array();
    }
    function get_unreaded_emails()
    {
        $this->db->where('deleted', 0);
        $this->db->where('readed', 0);
        $this->db->order_by('created_date', 'DESC');
        $query = $this->db->get('contactform');
        return $query->result_array();
    }

    // Delete Directory and all related
    public static function deleteDirectory($dir)
    {
        if (!file_exists($dir)) return true;
        if (!is_dir($dir)) return unlink($dir);
        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') continue;
            if (!self::deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) return false;
        }
        return rmdir($dir);
    }
    // upload file 
    public function uploadImage($image, $path)
    {
        $config = array();
        $config['allowed_types'] = 'jpeg|jpg|png|gif|txt|pdf|docx|doc|mp4';
        $config['upload_path'] = './uploads/' . $path;
        $config['max_size'] = '32768'; // 32MB
        $this->load->library('upload', $config);

        if (!$this->upload->do_upload($image, true)) {
            $this->upload_err = $this->upload->display_errors();
            return "";
        } else {
            $path = $this->upload->data();
            return  $path['file_name'];
        }
    }
    // create thumbs crop
    public function createthumb($name, $table, $thumb_value)
    {
        $path = 'uploads/' . $table . '/';
        // Create an array that holds the various image sizes
        $configs = array();
        $sumb_val = explode(",", $thumb_value);
        foreach ($sumb_val as $key => $value) {
            list($width, $height) = explode("x", $value);
            $configs[] = array('source_image' => $name, 'new_image' => $value . "/" . $name, 'width' => $width, 'height' => $height, 'maintain_ratio' => FALSE);
        }
        $this->load->library('image_lib');
        foreach ($configs as $config) {
            $this->image_lib->thumb($config, FCPATH . '' . $path);
        }
    }

    // create thumbs resize
    public function createthumb1($name, $table, $thumb_value)
    {
        $path = 'uploads/' . $table . '/';
        // Create an array that holds the various image sizes
        $configs = array();
        $sumb_val = explode(",", $thumb_value);
        foreach ($sumb_val as $key => $value) {
            list($width, $height) = explode("x", $value);
            $configs[] = array('source_image' => $name, 'new_image' => $value . "/" . $name, 'width' => $width, 'height' => $height, 'maintain_ratio' => TRUE);
        }
        $this->load->library('image_lib');
        foreach ($configs as $config) {
            $this->image_lib->thumb($config, FCPATH . '' . $path);
        }
    }


    public function date_in_formate($date)
    {
        list($m, $d, $y) = explode('/', $date);
        $date = $y . "-" . $m . "-" . $d;
        return $date;
    }

    public function date_out_formate($date)
    {
        if (!empty($date) && $date != "0000-00-00") {
            list($y, $m, $d) = explode('-', $date);
            $date = $m . "/" . $d . "/" . $y;
        } else {
            $date = "";
        }
        return $date;
    }


    // Delete Related images when delete any items from the recycle Bin .
    public function deleter_related_images($content_type, $id)
    {
        // Security: values come from the recycle-bin form and are used in SQL
        $id = (int) $id;
        $content_type = preg_replace('/[^A-Za-z0-9_ ]/', '', $content_type);
        $tables = str_replace(" ", "_", $content_type);
        $q1 = "SELECT * FROM `" . $tables . "` WHERE id_" . $tables . " = " . $id;
        $query1 = $this->db->query($q1);
        $row = $query1->row_array();
        $q = "SELECT name,thumb_val,thumb FROM `content_type_attr` WHERE id_content = (SELECT id_content FROM `content_type` WHERE name ='" . $content_type . "') AND (type = 4 OR type =5)";
        $query = $this->db->query($q);
        $related = $query->result_array();

        foreach ($related as $vv) {
            if ($row[$vv["name"]] != '') {
                if (file_exists('./uploads/' . $tables . '/' . $row[$vv["name"]])) {
                    unlink('./uploads/' . $tables . '/' . $row[$vv["name"]]);
                }
                if ($vv["thumb"] == 1) {
                    $sumb_val0 = explode(",", $vv["thumb_val"]);
                    foreach ($sumb_val0 as $key => $value) {
                        if (file_exists('./uploads/' . $tables . '/' . $value . '/' . $row[$vv["name"]])) {
                            unlink("./uploads/" . $tables . "/" . $value . "/" . $row[$vv["name"]]);
                        }
                    }
                }
            }
        }
    }




    // test pagination
    public function record_count()
    {
        return $this->db->count_all("country");
    }

    public function fetch_countries($limit, $start)
    {
        $this->db->limit($limit, $start);
        $query = $this->db->get("country");

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }
}
