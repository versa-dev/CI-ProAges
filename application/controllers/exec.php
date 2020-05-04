<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Exec extends CI_Controller {

	public function updateCapitalizeNames()
	{
		$query = $this->db->get('products');
		$products = $query->result_array();
		foreach ($products as $product){
			$obj = array(
				"name" => ucwords(strtolower($product["name"])),
			);
			$this->db->where('id', $product["id"]);
			$this->db->update('products', $obj);
		}

		$query = $this->db->get('work_order_status');
		$status = $query->result_array();
		foreach ($status as $stat){
			$obj = array(
				"name" => ucwords(strtolower($stat["name"])),
			);
			$this->db->where('id', $stat["id"]);
			$this->db->update('work_order_status', $obj);
		}
		$obj = array(
			"name" => "Póliza NTU",
		);
		$this->db->where('id', 10);
			$this->db->update('work_order_status', $obj);
		echo "Finalizo!!";
	}

	public function repairNegociosPai(){
		$query = $this->db->query('select * from payments where (select count(*) from policy_negocio_pai where policy_number = payments.policy_number) = 0 and year_prime = 1 and valid_for_report = 1 and year(payment_date) = 2017 ORDER BY policy_number asc, payment_date asc');
		$payments = $query->result_array();
		$policies = array();
		foreach ($payments as $i => $payment) {
			$policies[$payment["policy_number"]][] = $payment;
		}

		$count=0;
		foreach ($policies as $policy_id => $policy) {
			$total = 0;
			$flag = true;
			foreach ($policy as $payment) {
			 	$total += (int)$payment["amount"];
				if($flag && $total > 12000){
					$payment_date = $payment["payment_date"];
					$flag = false;
					echo $policy_id." $payment_date";
					$count++;
				}
			 }
			 if(!$flag) echo " $total<br>";
		}
		echo $count. " " . count($policies);	
	}

}

/* End of file exec.php */
/* Location: ./application/controllers/exec.php */