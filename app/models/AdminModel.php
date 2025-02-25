<?php

namespace bng\Models;

use bng\Models\BaseModel;

class AdminModel extends BaseModel
{
    public function get_all_clients()
    {
        // gets all clients from all agents
        $this->db_connect();
        $results = $this->query(
            "SELECT " .
                "p.id, " .
                "AES_DECRYPT(p.name, '" . MYSQL_AES_KEY . "') name, " .
                "p.gender, " .
                "p.birthdate, " .
                "AES_DECRYPT(p.email, '" . MYSQL_AES_KEY . "') email, " .
                "AES_DECRYPT(p.phone, '" . MYSQL_AES_KEY . "') phone, " .
                "p.interests, " .
                "p.created_at, " .
                "AES_DECRYPT(a.name, '" . MYSQL_AES_KEY . "') agent " .
                "FROM persons p " .
                "LEFT JOIN agents a " .
                "ON p.id_agent = a.id " .
                "WHERE p.deleted_at IS NULL " .
                "ORDER BY created_at DESC"
        );

        return $results;
    }

    public function get_agents_clients_stats()
    {
        // gets total from agent's clients
        $sql =
            "SELECT * FROM (" .
            "SELECT " .
            "p.id_agent, " .
            "AES_DECRYPT(a.name, '" . MYSQL_AES_KEY . "') agente, " .
            "COUNT(*) total_clientes " .
            "FROM persons p " .
            "LEFT JOIN agents a " .
            "ON a.id = p.id_agent " .
            "WHERE p.deleted_at IS NULL " .
            "GROUP BY id_agent ) a " .
            "ORDER BY total_clientes DESC";

        $this->db_connect();
        $results = $this->query($sql);

        return $results->results;
    }
}
