<?php
class ModelModuleFaq extends Model {

    public function addQuestion($data) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "faq SET new_status = '1', interested_email = '" . $this->db->escape($data['question_email']) . "'");

        $question_id = $this->db->getLastId();

        $this->db->query("INSERT INTO " . DB_PREFIX . "faq_description SET question_id = '". $question_id ."', question_name = '" . $this->db->escape($data['question_text']) . "', language_id = '" . (int)$this->config->get('config_language_id') . "'");
    }

    public function getQuestions() {
        $sql = "SELECT * FROM " . DB_PREFIX . "faq f LEFT JOIN " . DB_PREFIX . "faq_description fd ON (f.question_id = fd.question_id) WHERE fd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND f.publish_status = '1' ORDER BY f.sort_order ASC";

        $query = $this->db->query($sql);

        return $query->rows;
    }

}
