<?php

function cekAkses($id_modul,$aksi='can_view')
{
    $CI =& get_instance();

    $id_level =
        $CI->session->userdata('id_level');

    $CI->db->where('id_level',$id_level);
    $CI->db->where('id_modul',$id_modul);

    $row =
        $CI->db->get('tb_akses')
        ->row();

    if(!$row)
    {
        return false;
    }

    return $row->$aksi == 1;
}

function cekAksesJson($id_modul, $aksi)
{
    if(!cekAkses($id_modul, $aksi))
    {
        echo json_encode([
            'result' => false,
            'message' => 'Anda tidak memiliki hak akses'
        ]);
        exit;
    }
}