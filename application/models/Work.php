<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Work extends CI_Model{
    public function login($table = null, $data = null){
        $val = $this->db->where($data)
                        ->get($table);
        return $val;               
    }
    public function insert_data($table, $data){
        $val = $this->db->insert($table, $data);
        return $val;
    }

    public function get_last_id(){
        $val = $this->db->insert_id();
        return $val;
    }

    public function select_data($table, $cond=null){
        if($cond == null){
            $data = $this->db->get($table);
        }else{
            $data = $this->db->where($cond)
                            ->get($table);
        }
        return $data->result();
        
    }
    public function select_destnict_data($table, $cond=null, $dist="id"){
        if($cond == null){
            $query = $this->db->distinct($dist)
                             ->get($table); // Produces: SELECT DISTINCT * FROM table
        }else{
            $this->db->select('*');
            $this->db->group_by($dist);
            $this->db->from($table);
            $this->db->where($cond);
            $query = $this->db->get();
//            return $query->result();
        }
        return $query->result();
        
    }
//    Search Data
    public function search_data($table, $cond){
        $result = $this->db->or_like($cond, "both")
                           ->get($table)
                           ->result();
        return $result;
    }
    
    //Select Sum
    public function select_sum($table, $cond=null, $col=null){
        $this->db->where($cond);
        $this->db->select_sum($col);
        $query = $this->db->get($table);
        return $query->result();
    }
    
    
    public function delete_data($table, $cond=null){
        //$data = $this->db->get($table)
        if($cond != null){
            return $this->db->delete($table, $cond);
        }else{
            return $this->db->delete($table);
        }
    }
    public function update_data($table, $data, $cond=null){
            return $this->db->update($table,$data,$cond);
    }
    
    //To sending the SMS
    public function send_sms($mob, $msg=null, $sender=null){
        if($msg == null){
            $msg = "Welcome to Skulous Software";
        }
        if($sender == null):
            $sender = "SKULUS";
        endif;
        $resPattern = "/^[a-z0-9A-Z]{24}$/";
        //$msgPattern = "/^[a-z0-9A-Z]{24}$/";
        /*Send SMS*/
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => "http://api.msg91.com/api/sendhttp.php?route=4&sender=$sender&mobiles=$mob&authkey=224991AuVykO8pSsz5b4313bf&encrypt=&message=$msg&flash=&unicode=&afterminutes=&response=&campaign=&country=91",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_SSL_VERIFYHOST => 0,
          CURLOPT_SSL_VERIFYPEER => 0,
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
          //echo "cURL Error #:" . $err;
          return false;
        } else {
          //echo $response;
          if(preg_match($resPattern, $response)):
             return true;
          else:
             return false;
          endif;
        }
    }

    public function do_upload($path, $file_name ,$img_name=null){
        $config['upload_path']          = $path;
        $config['allowed_types']        = 'gif|jpg|png';
        $config['max_size']             = 1000;
        $config['max_width']            = 1200;
        $config['max_height']           = 700;
        $this->load->library('upload', $config);
        if ( ! $this->upload->do_upload($file_name))
            {
                return false;
            }else{
                return true;
            }
        }
    
}
?>