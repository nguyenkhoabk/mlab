<?php
class ModelAccountStudent extends Model {
	public function addStudent($data) {
		// if (isset($data['customer_group_id']) && is_array($this->config->get('config_customer_group_display')) && in_array($data['customer_group_id'], $this->config->get('config_customer_group_display'))) {
			// $customer_group_id = $data['customer_group_id'];
		// } else {
			// $customer_group_id = $this->config->get('config_customer_group_id');
		// }
		
		// $this->load->model('account/customer_group');
		
		// $customer_group_info = $this->model_account_customer_group->getCustomerGroup($customer_group_id);
		
      	$this->db->query("INSERT INTO " . DB_PREFIX . "hocvien SET firstname = '" . $this->db->escape($data['firstname']) . "', 
																	lastname = '" . $this->db->escape($data['lastname']) . "', 
																	email = '" . $this->db->escape($data['email']) . "', 
																	telephone = '" . $this->db->escape($data['telephone']) . "', 
																	address = '" . $this->db->escape($data['address_1']) . "', 
																	khoahoc = '" . $this->db->escape($data['ten_khoa_hoc']) . "'");
      	
		$student_id = $this->db->getLastId();
			
      	$this->db->query("INSERT INTO " . DB_PREFIX . "address SET 	customer_id = '" . (int)$student_id . "', 
																	firstname = '" . $this->db->escape($data['firstname']) . "', 
																	lastname = '" . $this->db->escape($data['lastname']) . "', 
																	address_1 = '" . $this->db->escape($data['address_1']) . "', 
																	city = '" . $this->db->escape($data['city']) . "', 
																	country_id = '" . (int)$data['country_id'] . "', 
																	zone_id = '" . (int)$data['zone_id'] . "'");
		
		$address_id = $this->db->getLastId();

      	$this->db->query("UPDATE " . DB_PREFIX . "hocvien SET address = '" . (int)$address_id . "' WHERE hocvien_id = '" . (int)$student_id . "'");
		
		$this->language->load('mail/student');
		
		$subject = sprintf($this->language->get('text_subject'), $this->config->get('config_name'));
		
		$message = sprintf($this->language->get('text_welcome'), $this->config->get('config_name')) . "\n\n";
		
		// if (!$customer_group_info['approval']) {
			// $message .= $this->language->get('text_login') . "\n";
		// } else {
			// $message .= $this->language->get('text_approval') . "\n";
		// }
		
		// $message .= $this->url->link('account/login', '', 'SSL') . "\n\n";
		$message .= $this->language->get('text_services') . "\n\n";
		$message .= $this->language->get('text_thanks') . "\n";
		$message .= $this->config->get('config_name');
		
		$mail = new Mail();
		$mail->protocol = $this->config->get('config_mail_protocol');
		$mail->parameter = $this->config->get('config_mail_parameter');
		$mail->hostname = $this->config->get('config_smtp_host');
		$mail->username = $this->config->get('config_smtp_username');
		$mail->password = $this->config->get('config_smtp_password');
		$mail->port = $this->config->get('config_smtp_port');
		$mail->timeout = $this->config->get('config_smtp_timeout');				
		$mail->setTo($data['email']);
		$mail->setFrom($this->config->get('config_email'));
		$mail->setSender($this->config->get('config_name'));
		$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
		$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
		$mail->send();
		
		// Send to main admin email if new account email is enabled
		if ($this->config->get('config_account_mail')) {
			$mail->setTo($this->config->get('config_email'));
			$mail->send();
			
			// Send to additional alert emails if new account email is enabled
			$emails = explode(',', $this->config->get('config_alert_emails'));
			
			foreach ($emails as $email) {
				if (strlen($email) > 0 && preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $email)) {
					$mail->setTo($email);
					$mail->send();
				}
			}
		}
	}
	
	// public function editCustomer($data) {
		// $this->db->query("UPDATE " . DB_PREFIX . "customer SET firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "' WHERE customer_id = '" . (int)$this->customer->getId() . "'");
	// }

	// public function editPassword($email, $password) {
      	// $this->db->query("UPDATE " . DB_PREFIX . "customer SET password = '" . $this->db->escape(md5($password)) . "' WHERE email = '" . $this->db->escape($email) . "'");
	// }

	// public function editNewsletter($newsletter) {
		// $this->db->query("UPDATE " . DB_PREFIX . "customer SET newsletter = '" . (int)$newsletter . "' WHERE customer_id = '" . (int)$this->customer->getId() . "'");
	// }
					
	public function getStudent($customer_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "hocvien WHERE hocvien_id = '" . (int)$hocvien_id . "'");
		
		return $query->row;
	}
	
	public function getStudentByEmail($email) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "hocvien WHERE email = '" . $this->db->escape($email) . "'");
		
		return $query->row;
	}
		
	// public function getCustomerByToken($token) {
		// $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE token = '" . $this->db->escape($token) . "' AND token != ''");
		
		// $this->db->query("UPDATE " . DB_PREFIX . "customer SET token = ''");
		
		// return $query->row;
	// }
		
	public function getStudents($data = array()) {
		$sql = "SELECT *, CONCAT(c.firstname, ' ', c.lastname) AS name 
				FROM " . DB_PREFIX . "hocvien c ";

		$implode = array();
		
		if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
			$implode[] = "LCASE(CONCAT(c.firstname, ' ', c.lastname)) LIKE '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
		}
		
		if (isset($data['filter_email']) && !is_null($data['filter_email'])) {
			$implode[] = "c.email = '" . $this->db->escape($data['filter_email']) . "'";
		}
		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}
		
		$sort_data = array(
			'name',
			'c.email',
		);	
			
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY name";	
		}
			
		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}
		
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}			

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}	
			
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}		
		
		$query = $this->db->query($sql);
		
		return $query->rows;	
	}
		
	public function getTotalCustomersByEmail($email) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "hocvien WHERE LOWER(email) = '" . $this->db->escape(strtolower($email)) . "'");
		
		return $query->row['total'];
	}
	
	// public function getIps($customer_id) {
		// $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_ip` WHERE customer_id = '" . (int)$customer_id . "'");
		
		// return $query->rows;
	// }	
	
	// public function isBlacklisted($ip) {
		// $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_ip_blacklist` WHERE ip = '" . $this->db->escape($ip) . "'");
		
		// return $query->num_rows;
	// }	
}
?>