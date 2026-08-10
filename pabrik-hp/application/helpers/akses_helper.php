<?php

function cekAkses($id_modul,$aksi='can_view')
{
    $CI =& get_instance();

    $role = $CI->session->userdata('role');

    $CI->db->where('role',$role);
    $CI->db->where('id_modul',$id_modul);

    $row = $CI->db->get('tb_akses')->row();

    if(!$row)
    {
        return false;
    }

    return $row->$aksi == 1;
}


function cekAksesJson($id_modul,$aksi)
{
    if(!cekAkses($id_modul,$aksi))
    {
        echo json_encode(array(

            'result'  => false,
            'message' => 'Anda tidak memiliki hak akses'

        ));

        exit;
    }
}