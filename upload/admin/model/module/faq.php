<?php
class ModelModuleFaq extends Model {
	public function addQuestion($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "faq SET sort_order = '" . (int)$data['sort_order'] . "', answered_status = '1', publish_status = '" . (int)$data['publish_status'] . "' ");

		$question_id = $this->db->getLastId();

		foreach ($data['question_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "faq_description SET question_id = '" . (int)$question_id . "', language_id = '" . (int)$language_id . "', question_name = '" . $this->db->escape($value['question']) . "',  answer = '" . $this->db->escape($value['answer']) ."'");
		}
	}

	public function editQuestion($question_id, $data) {
		$sql = "UPDATE " . DB_PREFIX . "faq SET sort_order = '" . (int)$data['sort_order'] . "', publish_status = '" . (int)$data['publish_status'] . "' ";

		if(isset($data['answer'])) {
			$sql.= ", answered_status = '1', new_status = '0' ";
		}

		$sql.= "WHERE question_id = '" . (int)$question_id . "'";

		$this->db->query($sql);

		$this->db->query("DELETE FROM " . DB_PREFIX . "faq_description WHERE question_id = '" . (int)$question_id . "'");

		foreach ($data['question_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "faq_description SET question_id = '" . (int)$question_id . "', language_id = '" . (int)$language_id . "', question_name = '" . $this->db->escape($value['question']) . "', answer = '" . $this->db->escape($value['answer']) . "'");
		}
	}

	public function deleteQuestion($question_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "faq WHERE question_id = '" . (int)$question_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "faq_description WHERE question_id = '" . (int)$question_id . "'");
	}

	public function getQuestion($question_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "faq f LEFT JOIN " . DB_PREFIX . "faq_description fd ON (f.question_id = fd.question_id) WHERE f.question_id = '" . (int)$question_id . "' AND fd.language_id = '" . $this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getQuestions($status) {
		$sql = "SELECT * FROM " . DB_PREFIX . "faq f LEFT JOIN " . DB_PREFIX . "faq_description fd ON (f.question_id = fd.question_id) WHERE fd.language_id = '" . (int)$this->config->get('config_language_id')."'";

		if($status) {
			$sql .= " AND f." . $status . "='1'";
		}

		$sql .= " ORDER BY f.sort_order ASC";

		$query = $this->db->query($sql);

		return $query->rows;
	}
	
	public function getQuestionDescriptions($question_id) {
		$essence_description_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "faq_description WHERE question_id = '" . (int)$question_id . "'");

		foreach ($query->rows as $result) {
			$essence_description_data[$result['language_id']] = array(
				'question'	=> $result['question_name'],
				'answer'	=> $result['answer']
			);
		}

		return $essence_description_data;
	}

	public function getTotalQuestions($status) {

    	$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "faq";

		if($status != '') {
			$sql .=  " WHERE " . $status . "='1'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}
}
