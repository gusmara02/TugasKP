<?php
class GajiBerkala_model extends CI_Model
{
    function getGajiBerkalaById($id)
    {
        $result = $this->db->get_where("gaji_berkala", ["id" => $id])->row_array();

        return $result;
    }

    function getAllGajiBerkala()
    {
        $result = $this->db->order_by('id', 'DESC')->get("gaji_berkala")->result_array();

        return $result;
    }

    function check($user)
    {
        $result = $this->db->like("check_in", date("Y-m-d"), "after")->get_where('absensi', ["id_user" => $user["id"]])->row();
        if ($result) {
            $disabled = (strtotime(date("Y-m-d H:i:s")) < strtotime(date("Y-m-d 16:00:00")));

            if (!$disabled && $result->check_out == null) {

                $this->db->set('check_out', date("Y-m-d H:i:s"));
                $this->db->where('id', $result->id);
                $this->db->update('absensi');
            } else {
                //Kondisi dimana klik untuk checkout namun belum waktunya
            }
        } else {
            $data = array(
                "id_user" => $user["id"],
                "role_id" => $user["role_id"],
                "nik" => $user["nik"],
                "nama" => $user["nama"],
                "bagian" => $user["bagian"],
                "check_in" => date("Y-m-d H:i:s")
            );
            $this->db->insert('absensi', $data);
        }
    }
}
