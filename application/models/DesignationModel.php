<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class DesignationModel extends CI_Model {

    var $table = 'tbl_designation';

    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_rows()
    {
        $this->db->from($this->table);
        $this->db->order_by('designation_name', 'ASC');
        $query = $this->db->get();

        return $query->result();
    }

}
