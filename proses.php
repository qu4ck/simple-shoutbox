<?php
session_start();

$conn = mysql_connect("localhost","root","toor") or die("Maaf belum terkoneksi ke database");
mysql_select_db("cb_sinau",$conn) or die("Maaf database tidak ditemukan");

$get = htmlentities($_GET['show']);
switch($get){
    case 'delete':
        if(!empty($_SESSION['username'])):
            /*$id = (int)$_POST['id'];
            $result = mysql_query("DELETE FROM tbl_shoutbox WHERE SOUT_ID = '".$id."'");

            if($result):
                $msg = array("message"  => "Berhasil menghapus data.", "valid" => TRUE);
            else:
                $msg = array("message"  => "Gagal menghapus data.", "valid" => FALSE);
            endif;            */
            $msg = array("message"  => "Versi Demo tidak bisa menghapus data.", "valid" => FALSE);
        else:
            $msg = array("message"     => "Silahkan login terlebih dahulu.", "valid" => FALSE);
        endif;
        echo json_encode($msg);
    break;
    case 'logout':
        if(!empty($_SESSION['username'])):
            session_destroy();
            $msg = array("valid" => TRUE);
        else:
            $msg = array("message"     => "Silahkan login terlebih dahulu.", "valid" => FALSE);
        endif;
        echo json_encode($msg);
    break;
    case 'login':
        $username = "admin";
        $password = md5('admin');
        
        if(empty($_SESSION['username'])):
            if(!empty($_POST['username'])):
                if(!empty($_POST['password'])):
                    if($username == htmlentities($_POST['username']) && $password == htmlentities(md5($_POST['password']))):
                        $_SESSION['username']   = $_POST['username']; // sesuaikan dengan session pada code kalian
                        $msg = array("valid" => TRUE);
                    else:
                        $msg = array("message"     => "Username/Password masih salah.", "valid" => FALSE);
                    endif;
                else:
                    $msg = array("message"     => "Password belum diisi.", "valid" => FALSE);
                endif;
            else:
                $msg = array("message"     => "Username belum diisi.", "valid" => FALSE);
            endif;
        else:
            $msg = array("message"     => "Saat ini anda telah login sebagai administrator.", "valid" => FALSE);
        endif;
        echo json_encode($msg);
    break;
    case 'add':
        if(!empty($_POST['nama'])){
            if(!empty($_POST['pesan'])){
                if(!empty($_POST['url'])){
                    if(filter_var($_POST['url'], FILTER_VALIDATE_URL) === FALSE){
                        $msg = array("message"     => "URL tidak Valid.", "valid" => FALSE);
                    }else{
                        $result = mysql_query("INSERT INTO tbl_shoutbox (`SOUT_NAMA`,`SOUT_URL`,`SOUT_PESAN`)VALUES('".htmlentities($_POST['nama'])."','".$_POST['url']."','".htmlentities($_POST['pesan'])."')");
                        $msg = array("message"     => "Gagal Input data.", "valid" => FALSE);
                    }
                }else{
                    $result = mysql_query("INSERT INTO tbl_shoutbox (`SOUT_NAMA`,`SOUT_PESAN`)VALUES('".htmlentities($_POST['nama'])."','".htmlentities($_POST['pesan'])."')");
                    $msg = array("message"     => "Gagal Input data.", "valid" => FALSE);
                }

                if($result){
                    $data = array("valid" => TRUE);
                }else{
                    $data = $msg;
                }
            }else{
                $data = array("message"     => "Pesan masih kosong.", "valid" => FALSE);
            }
        }else{
            $data = array("message"     => "Nama masih kosong.", "valid" => FALSE);
        }

        echo json_encode($data);
    break;
    default:
        require_once("pagination.class.php");
        $page = new Pagination();

        $page->lnk = "proses.php"; //ubah sesuai path server

        $sql = "SELECT SOUT_ID AS ID, SOUT_NAMA AS NAMA, SOUT_URL AS URL, SOUT_PESAN AS PESAN, DATE_FORMAT(TANGGAL,'%d %b %Y') AS TGL FROM tbl_shoutbox ORDER BY SOUT_ID DESC";
        $page->paging($sql,5);
        $query    = mysql_query($page->query);
        while($data = mysql_fetch_assoc($query)){
            $row[] = $data;
        }
        
        $login = (empty($_SESSION['username']))?FALSE:TRUE;
        if(count($row)>0){
            $data = array("list"        => $row, "valid"        => TRUE, "paging"       => $page->anchor,"login" => $login);
        }else{
            $data = array("message"     => "Belum ada data.", "valid"      => FALSE);
        }

        echo json_encode($data);
    break;
}
?>
