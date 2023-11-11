<?php
class Cuti_model extends CI_Model
{
    function getChartData($user_id = null)
    {
        $this->db->select('YEAR(input) AS year, jenis_cuti, COUNT(id) AS total_value');

        if ($user_id) {
            $this->db->where("id_user =", $user_id);
        }

        $this->db->group_by('YEAR(input)');
        $this->db->group_by('jenis_cuti');
        $results = $this->db->get('form_cuti')->result();

        $years = [];
        foreach ($results as $item) {
            if (!in_array($item->year, $years)) {
                $years[] = $item->year;
            }
        }

        $labels = [];
        foreach ($results as $item) {
            if (!in_array($item->jenis_cuti, $labels)) {
                $labels[] = $item->jenis_cuti;
            }
        }

        $datasets = [];
        foreach ($labels as $label) {
            $datas = [];
            foreach ($years as $year) {
                $total_value = 0;
                foreach ($results as $result) {
                    if ($result->year == $year && $result->jenis_cuti == $label) {
                        $total_value = (int)$result->total_value;
                    }
                }
                $datas[] = $total_value;
            }
            $datasets[] = [
                "label" => $label,
                "borderWidth" => 1,
                "data" => $datas
            ];
        }

        $data = [
            "labels" => $years,
            "datasets" => $datasets
        ];

        return $data;
    }
}
