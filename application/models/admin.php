<?php
class Admin extends CI_Model{
 

    protected $table = "admins";

    function __construct() {
        parent:: __construct();
    }

    public function authenticate($username, $password) {
        $getUser = $this->db->get_where('admins', ["username" => $username])->row();
        if (!$getUser)
            return false;

        $salt = $getUser->salt;

        $password = hash("SHA512", $salt . $password);
        $getUser->hasFullAccess = (bool) $this->hasFullAccess($getUser->id);

        if (!$getUser->active)
            return false;
        if ($password == $getUser->password)
            return $getUser;
        return false;
    }

    public function getByID($id) {
        $user = $this->db->get_where('admins', ["id" => $id])->row();
        if (!$user)
            return false;
        $logs = $this->logger->getByAdminID($id);

        $user->logs = $logs;
        $user->hasFullAccess = (bool) $this->hasFullAccess($id);

        return $user;
    }

    public function getAll($hidden = 1, $hideFullAccess = true) {
        return "ssss";
exit();

        $query = $this->db
                ->select("a.*, pg.name permissionGroup")
                ->from("{$this->table} a")
                ->join("permissionGroups pg", "pg.id = a.permissionGroupID", "inner")
                ->where(["hidden!=" => $hidden, "a.active"=>1]);
                // ->where(["admin" => 1]);

        if ($hideFullAccess)
            $query->where(["pg.hasFullAccess!=" => 1]);
//        $getUsers = $this->db->get_where($this->table, ["hidden!=" => $hidden])->result();


        return $query->get()->result();
    }

    public function getAllHierarchical($id, $includeParents = true) {
        $query = $this->db->query("with recursive adm as (
                            select     *
                            from       admins
                            where      parentID = (select parentID from admins where id = $id)
                            union all
                            select     p.*
                            from       admins p
                            inner join adm
                                    on p.parentID = adm.id
                          )
                          select a.*, pg.name permissionGroup 
                          from adm a 
                          inner join permissionGroups pg 
                          on pg.id = a.permissionGroupID and !a.hidden;")
                ->result();

        $parent = $this->db->query("WITH RECURSIVE adm AS (
                            SELECT * FROM admins WHERE id = 4

                            UNION ALL

                            SELECT p.* FROM admins p
                            JOIN adm t ON t.parentID = p.id
                        )
                        select a.*, pg.name permissionGroup 
                          from adm a 
                          inner join permissionGroups pg 
                          on pg.id = a.permissionGroupID and !a.hidden order by a.parentID;")
                ->result();

        if ($includeParents) {
            $query = array_merge($query, $parent);
        }

        return $query;
    }
    
    public function getByCustomerID($customer, $active = false) {
        $query = $this->db
                ->select("a.id")
                ->from("{$this->table} a")
                ->join("customersAdmins ca", "ca.adminID = a.id", "inner")
                ->where(["ca.customerID" => $customer]);

        if ($active)
            $query->where(["ca.active" => 1, "a.active" => 1]);
        
        return $query->get()->result();
    }
    
    public function getByBrand($brandID, $active = false) {
        $query = $this->db
                ->select("a.id, pg.name permissionGroup")
                ->from("{$this->table} a")
                ->join("brandsAdmins ba", "ba.adminID = a.id", "inner")
                ->join("permissionGroups pg", "a.permissionGroupID = pg.id", "inner")
                ->where(["ba.brandID" => $brandID]);

        if ($active)
            $query->where(["ba.active" => 1, "a.active" => 1]);
        
        return $query->get()->result();
    }

    public function isChildOf($id, $child) {
        $query = $this->db->query("with recursive adm as (
                            select     *
                            from       admins
                            where      parentID = (select parentID from admins where id = $id)
                            union all
                            select     p.*
                            from       admins p
                            inner join adm
                                    on p.parentID = adm.id
                          )
                          select a.*, pg.name permissionGroup 
                          from adm a 
                          inner join permissionGroups pg 
                          on pg.id = a.permissionGroupID and !a.hidden;")
                ->result();

        $ids = array_map(function($a) {
            return $a->id;
        }, $query);
        return in_array($child, $ids);
    }

    public function canAdd($admin) {
        return $admin->hasFullAccess or $this->admin->hasAccessTo($admin->id, "admins", ["add"]);
    }

    public function canEdit($admin, $target) {

        return ($admin->hasFullAccess or ( ($this->hasAccessTo($admin->id, "admins", ["edit"]) or $admin->id == $target->id) and $this->isChildOf($admin->id, $target->id)));
    }

    public function checkUsername($user) {
        $count = $this->db->get_where("admins", ["username" => $user, "hidden" => 0])->num_rows();
        if ($count)
            return $count;
        return 0;
    }

    public function checkEmail($email) {
        $count = $this->db->get_where("admins", ["email" => $email, "hidden" => 0])->num_rows();
        if ($count)
            return $count;
        return 0;
    }

    public function update($data, $id = 0, $password = false) {
        if (!$id) {

            $query = $this->db->insert('admins', $data);
            if (!$query)
                return false;
            return $this->db->insert_id();
        } else {
            if (!$password) unset($data['password']);
            $query = $this->db->update('admins', $data, ["id" => $id]);
        }

        if (!$query)
            return false;
        return true;
    }

    public function updateImage($img, $id) {
        $this->db->update("admins", ["image" => $img], ["id" => $id]);
    }

    public function hasFullAccess($id) {
        $query = $this->db
                ->select("pg.hasFullAccess")
                ->from("{$this->table} a")
                ->join("permissionGroups pg", "pg.id = a.permissionGroupID", "inner")
                ->where(["a.id" => $id, "pg.active" => 1])
                ->get()
                ->row();
        return (bool) $query->hasFullAccess;
    }

    public function hasAccessTo($id, $pages, $permissions) {
        if ($this->hasFullAccess($id))
            return true;

        if (!is_array($pages))
            $pages = array($pages);

        if (!is_array($permissions))
            $permissions = array($permissions);

        $pages = array_map(function($a) {
            return "access_to_$a";
        }, $pages);

        $queryString = implode(",", $pages);

        $query = $this->db
                ->select("p.key, pm.*")
                ->from("{$this->table} a")
                ->join("permissionMap pm", "pm.groupID = a.permissionGroupID", "inner")
                ->join("permissions p", "p.id = pm.permissionID", "inner")
                ->where(["a.id" => $id, "p.active" => 1, "pm.active" => 1, "a.active" => 1])
                ->where_in("p.key", $queryString)
                ->get()
                ->result();

        if (count($query) == 0)
            return false;

        foreach ($query as $o) {
            foreach ($permissions as $p) {
                if (!isset($o->$p) or ! $o->$p) {
                    return false;
                }
            }
        }

        return true;
    }
    
    public function getLoggedIn() {
        return $this->session->userdata('user');
    }
    
    public function saveToken($adminID, $token, $userAgent) {
        
    }

}
