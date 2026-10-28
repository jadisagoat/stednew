<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class PermissionPage extends CI_Model {

    var $table = "permissions";

    function __construct() {
        parent:: __construct();
    }

    public function getAll($groupID = null, $active = false) {
        if (!$groupID) {
            $query = $this->db
                    ->select("p.*")
                    ->from("{$this->table} p");

            return $query->get()->result();
        } else {
            $fields = $this->getFields();
            $string = "select p.*, $fields 
                    from permissions p 
                    left outer join permissionMap pm on p.id = pm.permissionID 
                    and (pm.groupID = $groupID or pm.groupID is null)
                    ";

            if ($active) {
                $string .= "and p.active";
            }
            $query = $this->db->query($string);
            $result = $query->result();
            return $result;
        }
    }

    public function getByID($pageID, $groupID = null) {
        if (!$groupID)
            return $this->noPermissions($pageID);

        $fields = $this->getFields();
        $string = "select p.*, $fields 
                    from permissions p 
                    inner join permissionMap pm on p.id = pm.permissionID 
                    and pm.permissionID = $pageID and p.active 
                    ";

        if ($groupID) {
            $string .= "and pm.groupID = $groupID ";
        }
        $query = $this->db->query($string);

        $row = $query->row();

        if (!$row) {
            return $this->noPermissions($pageID);
        }

        return $row;
    }

    function getFields() {
        $fields = array_map(function($a) {
            return "pm.$a,";
        }, $this->permission->permissionFields);
        $fields = implode("", $fields);
        $fields = substr($fields, 0, strlen($fields) - 1);

        return $fields;
    }

    function allPermissions($pageID) {
        $query = $this->db
                ->select("p.*")
                ->from("{$this->table} p")
                ->where(["p.id" => $pageID])
                ->get()
                ->row();
        $query = array_map(function($a) {
            foreach ($this->permission->permissionFields as $field) {
                $a->$field = 1;
                return $a;
            }
        }, $query);

        return $query;
    }

    function noPermissions($pageID) {
        $query = $this->db
                ->select("p.*")
                ->from("{$this->table} p")
                ->where(["p.id" => $pageID])
                ->get()
                ->row();
        foreach ($this->permission->permissionFields as $field) {
            $query->$field = 0;
        }

        return $query;
    }

    public function permissionExists($id, $groupID) {
        $query = $this->db
                ->select("pm.view")
                ->from("permissionMap pm")
                ->where(["pm.permissionID" => $id, "groupID" => $groupID, "pm.active" => 1]);

        $row = $query->get()->row();

        return $row;
    }

    public function getUserPermissions($userID, $groupID) {
        $query = $this->db
                ->select("p.*, pm.view, pm.add, pm.edit, pm.delete")
                ->from("{$this->table} p")
                ->join("permissionMap pm", "p.id = pm.permissionID", "inner")
                ->where(["pm.groupID" => $groupID]);
    }

    public function update($data, $id = 0) {
        if (!$id) {

            $query = $this->db->insert($this->table, $data);
            if (!$query)
                return false;
            return $this->db->insert_id();
        } else {
            $data["updated"] = date("Y-m-d H:i:s");
            $data["editorID"] = $this->session->userdata('user')->id;
            $query = $this->db->update($this->table, $data, ["id" => $id]);
        }

        if (!$query)
            return false;
        return true;
    }

}
