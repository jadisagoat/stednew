<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Logger extends CI_Model {

    function __construct() {
        parent:: __construct();
    }

    function addLog($data) {
        $this->db->insert('logs', $data);
    }

    function getLatestLog() {

        if ($this->admin->hasFullAccess($this->session->userdata('user')->id)) {
            $query = $this->db->select("*")
                    ->from("logs")
                    ->order_by("id", "desc")
                    ->limit(9);
            return $query->get()->result();
        } else {
            return $this->getExcludingFullAccess();
        }
    }

    function getByAdminID($admin) {
        return $this->db
                        ->select("*")
                        ->from("logs")
                        ->where(["userID" => $admin])
                        ->order_by("id", "desc")
                        ->limit(20)
                        ->get()
                        ->result();
    }

    function getExcludingFullAccess() {
        $query = $this->db->select("*")
                ->from("logs")
                ->where("userID != ", 1)
                ->order_by("id", "desc")
                ->limit(9);

        return $query->get()->result();
    }

}
