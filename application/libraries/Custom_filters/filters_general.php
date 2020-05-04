<?php
	$this->Name = "General";
	//Create group filter
	$group_args = array(
		"name" => "grupos",
		"id" => "grupos-filter",
		"class" => "select-2 filter-field",
		"style" => "width: 130px",
		"label" => "Todos los grupos",
		"default" => "",
		"filter_db" => "user_groups.id",
		"odb_table" => "user_groups",
		"odb_text" => "description",
		"odb_value" => "id",
		"process" => array(
			"director" => array(
				"sections" => array(
					"users-report" => array(
						"relationships" => array(
							"user_groups_vs_agents" => "agents.id = user_groups_vs_agents.agent_id",
						),
						"filters" => array(
							"user_groups_vs_agents.user_group_id" => "grupos",
						)
					)
				),
				"odb_filter" => array(
					"filter_type" => array("ramo", 3)
				)
			),
			"operations" => array(
				"sections" => array(
					"find-new-1" => array(
						"relationships" => array(
							"policies_vs_users as policies_users_C" => "policies_users_C.policy_id=work_order.policy_id",
							"user_groups_vs_agents" => "policies_users_C.user_id = user_groups_vs_agents.agent_id",
						),
						"filters" => array(
							"user_groups_vs_agents.user_group_id" => "grupos",
						)
					)
				),
				"elm_placeholder" => "Todos los grupos",
				"elm_label" => "Grupos:",
				"elm_template" => "#{label}<br>#{input}",
				"odb_filter" => array(),
			),
			"requests" => array(
				"sections" => array(
					"work-orders-get-group-by" => array(
						"relationships" => array(
							"user_groups_vs_agents" => "agents.id = user_groups_vs_agents.agent_id",
						),
						"filters" => array(
							"user_groups_vs_agents.user_group_id" => "grupos",
						)
					)
				),
				"elm_placeholder" => "Todos los grupos",
				"elm_label" => "Grupos:",
				"elm_template" => "#{label}<br>#{input}",
				"odb_filter" => array(),
			)
		)
	);
	$this->create_dropdown($group_args);