<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class rpm extends CI_Model{
	private $table = 'payments py';

	public function __construct(){
		parent::__construct();
	}

	public function getAllData($year, $ramo, $filter, $prime_type = "amount"){
		$this->db->select('year(py.payment_date) year,month(py.payment_date) month', FALSE);
		$this->db->select_sum("py." . $prime_type);
		$this->db->where('year(py.payment_date)', $year);
		$this->db->where('py.year_prime', 1);
		$this->db->where('product_group', $ramo);
		if(!empty($filter["agent"])){
			$this->db->where('py.agent_id', $filter["agent"]);
		}
		$this->db->group_by('year, month');
		$this->db->order_by('month', 'asc');
		$q = $this->db->get($this->table);
		$result = $q->result_array();
		//Fill the blank months
		$payments = $this->fillMonths($result, "month", $prime_type);
		return $payments;
	}

	public function getPolicies(){
		$this->db->select('po.id,po.prima,po.uid');
		$q = $this->db->get('policies po');
		$result = $q->result_array();
		$return = array();
		foreach ($result as $row) 
			$return[$row["uid"]] = $row; 
		return $return;
	}

	public function getPrimas($year, $ramo, $filter){
		$this->db->select_sum("prima");
		$this->db->where('year(wo.creation_date)', $year);
		$this->db->where('wo.product_group_id', $ramo);
		if(!empty($filter["product"])){
			$this->db->where('po.product_id', $filter["product"]);
		}
		$this->db->where_in('wo.work_order_status_id', array(4, 7));
		$this->db->join('policies po', 'po.id = wo.policy_id');
		$q = $this->db->get("work_order wo");
		$result = $q->row_array();
		return $result['prima'];
	}

	public function getPrimasProduct($year, $ramo, $filter){
		$this->db->select('pr.name');
		$this->db->select_sum('po.prima');
		$this->db->join('policies AS po', 'po.id = wo.policy_id', 'LEFT');
		$this->db->join('products AS pr', 'pr.id = po.product_id', 'LEFT');
		if(!empty($filter["product"])){
			$this->db->where('po.product_id', $filter["product"]);
		}
		$this->db->where('year(wo.creation_date)', $year);
		$this->db->where('wo.product_group_id', $ramo);
		$this->db->where_in('wo.work_order_status_id', array(4, 7));
		$this->db->group_by('pr.name');
		$this->db->order_by('pr.name', 'ASC');
		$q = $this->db->get('work_order wo');
		$result = $q->result_array();

		$products = array();

		foreach ($result as $i => $prima) {
			if(!empty($prima["prima"])){
				array_push($products, $prima);
			}
		}

		return $products;
	}

	public function getPrimasList($year, $ramo, $filter){
		$this->db->select('year(wo.creation_date) year,month(wo.creation_date) month');
		$this->db->join('policies po', 'po.id = wo.policy_id');
		$this->db->select_sum("prima");
		if(!empty($filter["product"])){
			$this->db->where('po.product_id', $filter["product"]);
		}
		$this->db->where('year(wo.creation_date)', $year);
		$this->db->where('wo.product_group_id', $ramo);
		$this->db->where_in('wo.work_order_status_id', array(4, 7));
		$this->db->order_by('month', 'asc');
		$this->db->group_by('month');
		$q = $this->db->get("work_order wo");
		$result = $q->result_array();
		$primas = $negocios = $this->fillMonths($result, "month", "prima");
		return $primas;
	}

	public function getNegociosList($year, $ramo, $filter){
		$this->db->select('month(wo.creation_date) month, count(*) negocios', FALSE);
		$this->db->join('work_order_types wot', 'wot.id = wo.work_order_type_id');
		$this->db->join('policies po', 'po.id = wo.policy_id');
		if(!empty($filter["product"])){
			$this->db->where('po.product_id', $filter["product"]);
		}
		$this->db->where('year(wo.creation_date)', $year);
		$this->db->where('wo.product_group_id', $ramo);
		$this->db->where('wo.work_order_status_id', 4);
		$this->db->where_in('wot.patent_id', array(47, 90));
		$this->db->order_by('month', 'asc');
		$this->db->group_by('month');
		$q = $this->db->get("work_order wo");
		$result = $q->result_array();

		//Fill the blank months
		$negocios = $this->fillMonths($result, "month", "negocios");
		return $negocios;
	}

	public function getNegociosProduct($year, $ramo, $filter){
		$this->db->select('COUNT(*) AS cantidad, pr.name', false);
		$this->db->join('work_order_types AS wot', 'wot.id = wo.work_order_type_id', 'LEFT');
		$this->db->join('policies', 'policies.id = wo.policy_id', 'LEFT');
		$this->db->join('products AS pr', 'pr.id = policies.product_id', 'LEFT');
		
		if(!empty($filter["product"])){
			$this->db->where('pr.id', $filter["product"]);
		}

		$this->db->where('YEAR(wo.creation_date)', $year);
		$this->db->where('wo.product_group_id', $ramo);
		$this->db->where('wo.work_order_status_id', 4);
		$this->db->where_in('wot.patent_id', array(47, 90));
		$this->db->group_by('pr.name');
		$this->db->order_by('pr.name', 'ASC');
		$q = $this->db->get('work_order AS wo');
		$result = $q->result_array();

		return $result;
	}

	public function getFirstPaymentYear(){
		$this->db->select('min(year(py.payment_date)) year');
		$q = $this->db->get($this->table);
		$row = $q->row_array();
		return isset($row["year"]) ? $row["year"] : date("Y");
	}

    public function getDataByGeneracion($year, $ramo, $filter, $prime_type = "amount")
    {
        $this->load->helper('agent/generations');
        if ($ramo == 1) { // Es Vida
            log_message('error', "The ramo = " . $ramo . "\n");
            log_message('error', "The year = " . $year);
            $generacion1_total = $this->getPaymentGenerationArray($year, $filter, $ramo, 'generacion_1', $prime_type);

            $generacion2_total = $this->getPaymentGenerationArray($year, $filter,$ramo, 'generacion_2', $prime_type);

            $generacion3_total = $this->getPaymentGenerationArray($year, $filter,$ramo, 'generacion_3', $prime_type);

            $generacion4_total = $this->getPaymentGenerationArray($year, $filter, $ramo, 'generacion_4', $prime_type);

			$generacion_consolidado_total = $this->getPaymentGenerationArray($year, $filter, $ramo, 'consolidado', $prime_type);
			
            return array($generacion1_total, $generacion2_total, $generacion3_total,$generacion4_total, $generacion_consolidado_total);
        } elseif ($ramo == 2) { // Es GMM

            $generacion1_total = $this->getPaymentGenerationArray($year, $filter, $ramo, 'generacion_1', $prime_type);

            $generacion2_total = $this->getPaymentGenerationArray($year, $filter, $ramo, 'generacion_2', $prime_type);

            $generacion3_total = $this->getPaymentGenerationArray($year, $filter, $ramo, 'generacion_3', $prime_type);

			$generacion4_total = $this->getPaymentGenerationArray($year, $filter, $ramo, 'generacion_4', $prime_type);

            $generacion_consolidado_total = $this->getPaymentGenerationArray($year, $filter, $ramo, 'consolidado', $prime_type);

            return array($generacion1_total, $generacion2_total, $generacion3_total, generacion4_total, $generacion_consolidado_total);
        }
        return array(0, 0, 0, 0, 0);
    }

    /**
     * @param $generationDateRange
     * @param $year
     * @param $filter
     * @param $ramo
     * @param $generation_id
     * @return int
     */
    public function getPaymentGenerationArray($year, $filter, $ramo, $generation_id, $prime_type = "amount")
    {
		if (!empty($filter["agent"])) {
		    switch ($generation_id) {
		        case 'generacion_1':
		            $sql = "SELECT SUM(py." . $prime_type . ") as total
		                    FROM payments AS py
							WHERE py.agent_generation = 'Generación 1'
							AND py.year_prime = 1
		                    AND py.product_group = ?
		                    AND py.payment_date like ?
		                    AND py.agent_id = ?";
		            $q = $this->db->query($sql, array($ramo, $year."%", $filter["agent"]));
		            break;
		        case 'generacion_2':
		            $sql = "SELECT SUM(py." . $prime_type . ") as total
		                    FROM payments AS py
							WHERE py.agent_generation = 'Generación 2'
							AND py.year_prime = 1
		                    AND py.product_group = ?
		                    AND py.payment_date like ?
		                    AND py.agent_id = ?";
		            $q = $this->db->query($sql, array($ramo, $year."%", $filter["agent"]));
		            break;
		        case 'generacion_3':
		            $sql = "SELECT SUM(py." . $prime_type . ") as total
		                    FROM payments AS py
							WHERE py.agent_generation = 'Generación 3'
							AND py.year_prime = 1
		                    AND py.product_group = ?
		                    AND py.payment_date like ?
		                    AND py.agent_id = ?";
		            $q = $this->db->query($sql, array($ramo, $year."%", $filter["agent"]));
		            break;
		        case 'generacion_4':
		            $sql = "SELECT SUM(py." . $prime_type . ") as total
		                    FROM payments AS py
							WHERE py.agent_generation = 'Generación 4'
							AND py.year_prime = 1
		                    AND py.product_group = ?
		                    AND py.payment_date like ?
		                    AND py.agent_id = ?";
		            $q = $this->db->query($sql, array($ramo, $year."%", $filter["agent"]));
		            break;
		        case 'consolidado':
		            $sql = "SELECT SUM(py." . $prime_type . ") as total
		                    FROM payments AS py
							WHERE py.agent_generation = 'Consolidado'
							AND py.year_prime = 1
		                    AND py.product_group = ?
		                    AND py.payment_date like ?
		                    AND py.agent_id = ?";
		            $q = $this->db->query($sql, array($ramo, $year."%", $filter["agent"]));
		            break;
		    }
		} else {
		    switch ($generation_id) {
		        case 'generacion_1':
		            $sql = "SELECT SUM(py." . $prime_type . ") as total
		                    FROM payments AS py
							WHERE py.agent_generation = 'Generación 1'
							AND py.year_prime = 1
		                    AND py.product_group = ?
		                    AND py.payment_date like ?";
		            $q = $this->db->query($sql, array($ramo, $year."%"));
		            break;
		        case 'generacion_2':
		            $sql = "SELECT SUM(py." . $prime_type . ") as total
		                    FROM payments AS py
							WHERE py.agent_generation = 'Generación 2'
							AND py.year_prime = 1
		                    AND py.product_group = ?
		                    AND py.payment_date like ?";
		            $q = $this->db->query($sql, array($ramo, $year."%"));
		            break;
		        case 'generacion_3':
		            $sql = "SELECT SUM(py." . $prime_type . ") as total
		                    FROM payments AS py
							WHERE py.agent_generation = 'Generación 3'
							AND py.year_prime = 1
		                    AND py.product_group = ?
		                    AND py.payment_date like ?";
		            $q = $this->db->query($sql, array($ramo, $year."%"));
		            break;
		        case 'generacion_4':
		            $sql = "SELECT SUM(py." . $prime_type . ") as total
		                    FROM payments AS py
							WHERE py.agent_generation = 'Generación 4'
							AND py.year_prime = 1
		                    AND py.product_group = ?
		                    AND py.payment_date like ?";
		            $q = $this->db->query($sql, array($ramo, $year."%"));
		            break;
		        case 'consolidado':
		            $sql = "SELECT SUM(py." . $prime_type . ") as total
		                    FROM payments AS py
							WHERE py.agent_generation = 'Consolidado'
							AND py.year_prime = 1
		                    AND py.product_group = ?
		                    AND py.payment_date like ?";
		            $q = $this->db->query($sql, array($ramo, $year."%"));
		            break;
		    }
		}
		foreach ($q->result() as $row){
		    $amount_total = $row->total;
		}
		return $amount_total;
    }

	public function getDataByProduct($year, $ramo, $filter, $prime_type = "amount"){
		//Get all products of ramo
		$this->db->select('po.uid, pr.id, pr.name');
		$this->db->join('products pr', 'po.product_id = pr.id', "left");
		if(!empty($filter["product"])){
			$this->db->where('pr.id', $filter["product"]);
		}
		$this->db->order_by('pr.id', 'asc');
		$this->db->group_by('po.uid');
		$q = $this->db->get('policies po');
		$policies = $q->result_array();
		
		//Create array of with all policies grouped by product (Enhaces the direct query speed)
		$products = array();
		foreach ($policies as $policy) {
			$products[$policy["id"]]["name"] = $policy["name"];
			$products[$policy["id"]]["id"] = $policy["id"];
		}

		if(isset($products[""])){
			$products[""]["name"] = "No clasificado";
		}

		if(!empty($filter["agent"])){
			$sql = 'SELECT py.' . $prime_type . ', pr.id, year(py.payment_date) AS year, month(py.payment_date) AS month
				FROM payments AS py
				LEFT JOIN policies AS po ON po.uid = py.policy_number
				LEFT JOIN products AS pr ON pr.id = po.product_id
				WHERE year_prime = 1 AND product_group = ? AND YEAR(py.payment_date) = ? AND py.agent_id = ?
				GROUP BY py.pay_tbl_id ORDER BY month ASC';

			$q = $this->db->query($sql, array($ramo, $year, $filter["agent"]));
			$paymentsResult = $q->result_array();
		}else{
			$sql = 'SELECT py.' . $prime_type . ', pr.id, year(py.payment_date) AS year, month(py.payment_date) AS month
				FROM payments AS py
				LEFT JOIN policies AS po ON po.uid = py.policy_number
				LEFT JOIN products AS pr ON pr.id = po.product_id
				WHERE year_prime = 1 AND product_group = ? AND YEAR(py.payment_date) = ?
				GROUP BY py.pay_tbl_id ORDER BY month ASC';

			$q = $this->db->query($sql, array($ramo, $year));
			$paymentsResult = $q->result_array();
		}
		
		foreach ($products as $i => $product) {
			$result = array();
			$amountMonth = array();

			foreach ($paymentsResult as $key => $value) {
				if($value["id"] == $product["id"]){
					if(isset($amountMonth[$value["month"]])){
						$amountMonth[$value["month"]] += $value[$prime_type];
					}else{
						$amountMonth[$value["month"]] = $value[$prime_type];
					}
				}
			}

			foreach ($amountMonth as $key => $value) {
				array_push($result, array("year" => $year, "month" => $key, $prime_type => $value));
			}

			$payments = $this->fillMonths($result, "month", $prime_type);
			$products[$i]["payments"] = $payments;
		}

		//Delete the products without payments
		foreach ($products as $i => $product) {
			$delete = TRUE;
			foreach ($product["payments"] as $payment){
				if($payment != 0)
					$delete = FALSE;
			}
			if($delete)
				unset($products[$i]);
		}
		return $products;
	}

	public function getDataByProductMonth($filter){
		$whered = 'WHERE year_prime = 1 AND product_group = ? AND YEAR(py.payment_date) = ? AND MONTH(py.payment_date) = ?';
		$dwhere = array($filter["ramo"], $filter["periodo"], $filter["month_search"]);
		
		if(!empty($filter["agent"])){
			array_push($dwhere, $filter["agent"]);
			$whered .= ' AND py.agent_id = ?';
		}

		if(!empty($filter["product"])){
			array_push($dwhere, $filter["product"]);
			$whered .= ' AND pr.id = ?';
		}

		$sql = "SELECT py.*, po.name AS asegurado, po.period, pr.id, pr.name AS producto
			FROM payments AS py
			LEFT JOIN policies AS po ON po.uid = py.policy_number
			LEFT JOIN products AS pr ON pr.id = po.product_id
			$whered
			GROUP BY py.pay_tbl_id";

		$q = $this->db->query($sql, $dwhere);
		$paymentsResult = $q->result_array();

		$payments = array();

		foreach ($paymentsResult as $key => $value) {
			if($value["id"] == $filter["producto"]){
				array_push($payments, $value);
			}
		}
		return $payments;
	}

    public function getDataByGeneration($filter){
        $this->load->helper('agent/generations');
        $year = $filter["periodo"];
        $ramo = $filter["ramo"];

        $whered = 'WHERE year_prime = 1 AND product_group = ? AND YEAR(py.payment_date) = ?';
        $dwhere = array($filter["ramo"], $filter["periodo"]);

        if(!empty($filter["agent"])){
            array_push($dwhere, $filter["agent"]);
            $whered .= ' AND py.agent_id = ?';
        }

        switch ($filter["generacion"]) {
            case 'Generacion 1':
                $whered .= " AND py.agent_generation = 'Generación 1'";
                break;
            case 'Generacion 2':
                $whered .= " AND py.agent_generation = 'Generación 2'";
                break;
            case 'Generacion 3':
                $whered .= " AND py.agent_generation = 'Generación 3'";
                break;
            case 'Generacion 4':
                $whered .= " AND py.agent_generation = 'Generación 4'";
                break;
            case 'Consolidado':
                $whered .= " AND py.agent_generation = 'Consolidado'" ;
                break;
        }

        $sql = "SELECT py.*, po.name AS asegurado, po.period, pr.id, pr.name AS producto
			FROM payments AS py
			LEFT JOIN policies AS po ON po.uid = py.policy_number
			LEFT JOIN products AS pr ON pr.id = po.product_id
			LEFT JOIN agents AS a ON a.id = py.agent_id
			$whered
			GROUP BY py.pay_tbl_id";

		$q = $this->db->query($sql, $dwhere);
		$paymentsResult = $q->result_array();	

        $payments = array();

        foreach ($paymentsResult as $key => $value) {
            array_push($payments, $value);
        }
        return $payments;
    }

	public function getNegocios($year, $ramo, $filter){
		$this->db->join('work_order_types wot', 'wot.id = wo.work_order_type_id');
		$this->db->where('year(wo.creation_date)', $year);
		$this->db->where('wo.product_group_id', $ramo);
		$this->db->where('wo.work_order_status_id', 4);
		$this->db->where_in('wot.patent_id', array(47, 90));
		return $this->db->count_all_results("work_order wo");
	}


	public function getNumAgents($year, $ramo, $filter, $prime_type = "amount"){
		$this->db->select('sum(py.' . $prime_type . ') as val', FALSE);
		if($filter["agent"]){
			$this->db->where('py.agent_id', $filter["agent"]);
		}
		$this->db->where('py.product_group', $ramo);
		$this->db->where('py.year_prime', 1);
		$this->db->where('year(py.payment_date)', $year);
		$this->db->where('py.valid_for_report', 1);
		$this->db->group_by('py.agent_id');
		$this->db->having('val >= ', 385000); 
		$q = $this->db->get($this->table);
		$result = $q->result_array();
		return count($result);
	}

	public function getAgentsMonth($filter, $prime_type = "amount"){
		$months = $this->getZerosArray(12);
		$datos = array();
		foreach ($months as $key => $value) {
			$this->db->select('agent_id, sum(py.' . $prime_type . ') as val');
			$this->db->join('policies AS po', 'po.uid = py.policy_number', 'LEFT');
			$this->db->join('products AS pr', 'pr.id = po.product_id');
			
			if($filter["agent"]){
				$this->db->where('py.agent_id', $filter["agent"]);
			}

			if(!empty($filter["product"])){
				$this->db->where('pr.id', $filter["product"]);
			}

			$this->db->where('py.year_prime', 1);
			$this->db->where('py.product_group', $filter["ramo"]);
			$this->db->where('year(py.payment_date)', $filter["periodo"]);
			$this->db->where('month(py.payment_date)', $key+1);
			$this->db->where('py.valid_for_report', 1);
			$this->db->group_by('py.agent_id');
			// $this->db->having('val >= ', 385000); 
			$q = $this->db->get($this->table);
			$result = $q->result_array();

			array_push($datos, array(
				'month' => $key,
				'agents' => count($result)
			));
		}

		return $datos;
	}

	public function getAgentsProduct($filter){
		$whered = 'WHERE year_prime = 1 AND product_group = ? AND YEAR(py.payment_date) = ?';
		$dwhere = array($filter["ramo"], $filter["periodo"]);
		
		if(!empty($filter["agent"])){
			array_push($dwhere, $filter["agent"]);
			$whered .= ' AND py.agent_id = ?';
		}

		if(!empty($filter["product"])){
			array_push($dwhere, $filter["product"]);
			$whered .= ' AND pr.id = ?';
		}

		$sql = "SELECT IFNULL(id, 0) AS id, IFNULL(name, 'No disponible') AS name, agent_id, COUNT(*) AS total FROM (SELECT pr.id, pr.name, agent_id
			FROM payments AS py
			LEFT JOIN policies AS po ON po.uid = py.policy_number
			LEFT JOIN products AS pr ON pr.id = po.product_id
			$whered
			GROUP BY py.pay_tbl_id) AS payments GROUP BY id, agent_id";

		$q = $this->db->query($sql, $dwhere);
		$paymentsResult = $q->result_array();

		$agents = array();

		foreach ($paymentsResult as $key => $value) {
			$agents[$value["id"]]["name"] = $value["name"];
			if(isset($agents[$value["id"]]["agents"])){
				$agents[$value["id"]]["agents"] += 1;
			}else{
				$agents[$value["id"]]["agents"] = 1;
			}
		}

		return $agents;
	}

	public function getNumBusiness($year, $ramo, $filter){
		$this->db->select('sum(pai_business) as val', FALSE); 
		if(!empty($filter["product"])){
			$this->db->where('po.product_id', $filter["product"]);
		}
		$this->db->where('product_group', $ramo);
		$this->db->where('year(payment_date)', $year);
		$q = $this->db->get('payments');
		$result = $q->row_array();
		$val = $result['val'];
		$num = 0;
		if($val!=''){ $num = $val; }
		return $num;
	}

	public function fillMonths($data, $month_field, $value_field){
		$filled_months = $this->getZerosArray(12);
		foreach ($data as $row){
			$filled_months[$row[$month_field] - 1] = $row[$value_field];
		}
		return $filled_months;
	}

	public function getZerosArray($length){
		$zeros = array();
		for ($i=0; $i < $length; $i++) { 
			$zeros[$i] = 0;
		}
		return $zeros;
	}
}
?>