<?php
if(!class_exists('Requests')){
	require_once('Requests.php');
}
require_once('Requests/Auth.php');
require_once('Requests/Auth/Basic.php');

/**
 * Inicialize as:
 * 
 * $this->api = new CTApiClient( 'access_key_id', 'access_key_secret' );
 */
class CTApiClient {

	private
		$apiKey,
		$apiSecret,
		$headers,
		$className,
		$timeout = 60;

	public
		$data = array();

	const
		API_URL = 'https://my.cloudtalk.io/api';

	public function __construct($apiKey, $apiSecret){
		$this->className = get_class($this);
		$this->apiKey    = $apiKey;
		$this->apiSecret = $apiSecret;
		$this->options   = array(
			'auth' => new Requests_Auth_Basic(array($this->apiKey, $this->apiSecret)),
			'timeout' => $this->timeout
		);
	}

	private function setData($dataSet, $key, $value){
		if(is_array($key)){
			$this->data[$dataSet] = array_merge($this->data[$dataSet], $key);
		} else {
			$this->data[$dataSet][$key] = $value;
		}
	}

	private function getRequestParams($params){
		$request_params = '';
		
		if (!empty($params)) {
			$uriParams = http_build_query($params, '', '&');
			$request_params = '?'.rtrim($uriParams, '=');
		}
		
		return $request_params;
	}

	private function getConstant($const){
		return constant(get_class($this)."::".$const);
	}

	/***************** CALLS ***************/

	/**
	 * @api {get} /calls/index.json
	 * 
	 * $calls = $this->api->getCallHistory(array(
			'status' => 'missed',
			'limit' => 30,
			'page' => 1
		));
	 */
	public function getCallHistory($params = array()){
		if(!class_exists('Requests')){
			trigger_error("Unable to load Requests class", E_USER_WARNING);
			return false;
		}
		Requests::register_autoloader();

		$response = Requests::get($this->getConstant('API_URL').'/calls/index.json'.$this->getRequestParams($params), array(), $this->options);
		$response_data = json_decode($response->body);
		return $response_data;
	}

	/**
     * @api {get} /calls/recording/:id.json
     * 
     * $recordingData = $this->api->getRecording(call_id);
     */
	public function getRecording($id){
		if(!class_exists('Requests')){
			trigger_error("Unable to load Requests class", E_USER_WARNING);
			return false;
		}
		Requests::register_autoloader();

		$response = Requests::get($this->getConstant('API_URL').'/calls/recording/'.$id.'.json', array(), $this->options);

		if ($response->success) {
			return $response->body;
		} else {
			return json_decode($response->body);
		}
	}

    /**
     * @api {delete} /recordings/delete/:id.json
     *
     * $result = $this->api->deleteRecord(callId);
     */
    public function deleteRecord($id){
        if(!class_exists('Requests')){
            trigger_error("Unable to load Requests class", E_USER_WARNING);
            return false;
        }
        Requests::register_autoloader();
        $id = (int) $id;

        $response = Requests::delete($this->getConstant('API_URL').'/recordings/delete/'.$id.'.json', array(), $this->options);
        $response_data = json_decode($response->body);
        return $response_data;
    }

	/***************** AGENTS ***************/

	/**
	 * @api {get} /agents/index.json
	 * 
	 * $agents = $this->api->getAgents(array(
			'id' => '100',
			'limit' => 30,
			'page' => 1
		));
	 */
	public function getAgents($params = array()){
		if(!class_exists('Requests')){
			trigger_error("Unable to load Requests class", E_USER_WARNING);
			return false;
		}
		Requests::register_autoloader();

		$response = Requests::get($this->getConstant('API_URL').'/agents/index.json'.$this->getRequestParams($params), array(), $this->options);
		$response_data = json_decode($response->body);
		return $response_data;
	}

	/**
	 * @api {put} /agents/add.json
	 * 
	 * $result = $this->api->addAgent(array(
			'firstname' => 'John',
			'lastname' => 'Doe',
			'email' => 'john.doe@gmail.com'
		));
	 */
	public function addAgent($data){
		if(!class_exists('Requests')){
			trigger_error("Unable to load Requests class", E_USER_WARNING);
			return false;
		}
		Requests::register_autoloader();

		$response = Requests::put($this->getConstant('API_URL').'/agents/add.json', array(), $data, $this->options);
		$response_data = json_decode($response->body);
		return $response_data;
	}

	/**
	 * @api {post} /agents/edit/:id.json
	 * 
	 * $result = $this->api->editAgent(agentId, array(
			'firstname' => 'John',
			'lastname' => 'Doe',
			'email' => 'john.doe@gmail.com'
		));
	 */
	public function editAgent($id, $data = array()){
		if(!class_exists('Requests')){
			trigger_error("Unable to load Requests class", E_USER_WARNING);
			return false;
		}
		Requests::register_autoloader();
		$id = (int) $id;

		$response = Requests::post($this->getConstant('API_URL').'/agents/edit/'.$id.'.json', array(), $data, $this->options);
		$response_data = json_decode($response->body);
		return $response_data;
	}

	/**
	 * @api {delete} /agents/delete/:id.json
	 * 
	 * $result = $this->api->deleteAgent(agentId);
	 */
	public function deleteAgent($id){
		if(!class_exists('Requests')){
			trigger_error("Unable to load Requests class", E_USER_WARNING);
			return false;
		}
		Requests::register_autoloader();
		$id = (int) $id;

		$response = Requests::delete($this->getConstant('API_URL').'/agents/delete/'.$id.'.json', array(), $this->options);
		$response_data = json_decode($response->body);
		return $response_data;
	}


	/***************** NUMBERS ***************/

	/**
	 * @api {get} /numbers/index.json
	 * 
	 * $numbers = $this->api->getNumbers(array(
			'id' => '100',
			'country_code' => '421',
			'limit' => 30,
			'page' => 1
		));
	 */
	public function getNumbers($params = array()){
		if(!class_exists('Requests')){
			trigger_error("Unable to load Requests class", E_USER_WARNING);
			return false;
		}
		Requests::register_autoloader();

		$response = Requests::get($this->getConstant('API_URL').'/numbers/index.json'.$this->getRequestParams($params), array(), $this->options);
		$response_data = json_decode($response->body);
		return $response_data;
	}

	/**
	 * @api {post} /numbers/edit/:id.json
	 * 
	 * $result = $this->api->editNumber(numberId, array(
			'internal_name' => 'XYZ',
			'type' => '1',
			'agent_id' => '123'
		));
	 */
	public function editNumber($id, $data = array()){
		if(!class_exists('Requests')){
			trigger_error("Unable to load Requests class", E_USER_WARNING);
			return false;
		}
		Requests::register_autoloader();
		$id = (int) $id;

		$response = Requests::post($this->getConstant('API_URL').'/numbers/edit/'.$id.'.json', array(), $data, $this->options);
		$response_data = json_decode($response->body);
		return $response_data;
	}

	/**
	 * @api {delete} /numbers/clear/:id.json
	 * 
	 * $result = $this->api->clearNumber(numberId);
	 */
	public function clearNumber($id){
		if(!class_exists('Requests')){
			trigger_error("Unable to load Requests class", E_USER_WARNING);
			return false;
		}
		Requests::register_autoloader();
		$id = (int) $id;

		$response = Requests::delete($this->getConstant('API_URL').'/numbers/clear/'.$id.'.json', array(), $this->options);
		$response_data = json_decode($response->body);
		return $response_data;
	}

    /***************** CONTACTS ***************/
    /**
     * @api {get} /contacts/index.json
     * 
     * $contacts = $this->api->getContacts(array(
		    'tag_id' => 1,
		    'limit' => 30,
		    'page' => 1
		));
     */
    public function getContacts($params = array()){
        if(!class_exists('Requests')){
            trigger_error("Unable to load Requests class", E_USER_WARNING);
            return false;
        }
        Requests::register_autoloader();

        $response = Requests::get($this->getConstant('API_URL').'/contacts/index.json'.$this->getRequestParams($params), array(), $this->options);
        $response_data = json_decode($response->body);
        return $response_data;
    }

    /**
     * @api {get} /contacts/show/:id.json
     * 
     * $contacts = $this->api->showContact(contactId);
     */
    public function showContact($id){
        if(!class_exists('Requests')){
            trigger_error("Unable to load Requests class", E_USER_WARNING);
            return false;
        }
        Requests::register_autoloader();

        $response = Requests::get($this->getConstant('API_URL').'/contacts/show/' . $id .'.json', array(), $this->options);
        $response_data = json_decode($response->body);
        return $response_data;
    }

    /**
     * @api {delete} /contacts/delete/:id.json
     * 
     * $result = $this->api->deleteContact(contactId);
     */
    public function deleteContact($id){
        if(!class_exists('Requests')){
            trigger_error("Unable to load Requests class", E_USER_WARNING);
            return false;
        }
        Requests::register_autoloader();
        $id = (int) $id;

        $response = Requests::delete($this->getConstant('API_URL').'/contacts/delete/'.$id.'.json', array(), $this->options);
        $response_data = json_decode($response->body);
        return $response_data;
    }

    /**
     * @api {put} /contacts/add.json
     * 
     * $contacts = $this->api->addContact(array(
		    'name' => 'Aittokallio Sami',
		    'title' => 'Mgr.',
		    'ContactNumber' => array(
		        array(
		            'public_number' => '+442012345678'
		        ),
		        array(...)
		    ),
		    'ContactEmail' => array(
		        array(
		            'email' => 'Aittokallio@sami.com',
		        ),
		        array(...)
		    ),
		    'company' => 'Colorado Avalanche',
		    'industry' => 'nhl',
		    'website' => 'http://avalanche.nhl.com/',
		    'address' => 'First 33',
		    'city' => 'New York',
		    'zip' => '11804',
		    'state' => 'NY',
		    'country_id' => '123',
		    'favorite_agent' => '1234',
		    'ContactsTag' => array(
		        array(
		            'name' => 'VIP'
		        ),
		        array(...)
		   	),
		    'ContactAttribute' => array(
		        array(
		            'id' => 12,
		            'value' => 1804,
		        ),
		        array(...)
		    )
		));
     */
    public function addContact($data){
        if(!class_exists('Requests')){
            trigger_error("Unable to load Requests class", E_USER_WARNING);
            return false;
        }
        Requests::register_autoloader();

        $response = Requests::put($this->getConstant('API_URL').'/contacts/add.json', array(), $data, $this->options);
        $response_data = json_decode($response->body);
        return $response_data;
    }

    /**
     * @api {post} /contacts/edit/:id.json
     * 
     * $contacts = $this->api->editContact(contactId, array(
		    'name' => 'Aittokallio Sami',
		    'title' => 'Mgr.',
		    'company' => 'Colorado Avalanche',
		    'industry' => 'nhl',
		    'state' => 'NY',
		    'country_id' => '345',
		    'favorite_agent' => '454',
		    'ContactNumber' => array(
		        array(
		            'public_number' => '+442012345678'
		        ),
		        array(...)
		    ),
		    'ContactEmail' => array(
		        array(
		            'email' => 'Aittokallio@sami.com',
		        ),
		        array(...)
		    ),
		    'ContactsTag' => array(
		        array(
		            'name' => 'VIP'
		        ),
		        array(...)
		   	),
		    'ContactAttribute' => array(
		        array(
		            'id' => 12,
		            'value' => 1804,
		        ),
		        array(...)
		    )
		));
     */
    public function editContact($id, $data = array()){
        if(!class_exists('Requests')){
            trigger_error("Unable to load Requests class", E_USER_WARNING);
            return false;
        }
        Requests::register_autoloader();
        $id = (int) $id;

        $response = Requests::post($this->getConstant('API_URL').'/contacts/edit/'.$id.'.json', array(), $data, $this->options);
        $response_data = json_decode($response->body);
        return $response_data;
    }   

    /************* CONTACT NOTES ************/

    /**
     * @api {post} /notes/edit/:id.json
     * 
     * $note = $this->api->editNote(noteId, array(
		    'user_id' => 522,
		    'contact_id' => 9,
		    'note' => 'Lorem ipsum',
		));
     */
    public function editNote($id, $data = array()){
        if(!class_exists('Requests')){
            trigger_error("Unable to load Requests class", E_USER_WARNING);
            return false;
        }
        Requests::register_autoloader();
        $id = (int) $id;

        $response = Requests::post($this->getConstant('API_URL').'/notes/edit/'.$id.'.json', array(), $data, $this->options);
        $response_data = json_decode($response->body);
        return $response_data;
    } 

    /**
     * @api {put} /notes/add/:id.json
     * 
     * $note = $this->api->addNote(contactId, array(
		    'user_id' => 522,
		    'note' => 'Lorem ipsum',
		));
     */
    public function addNote($id, $data){
        if(!class_exists('Requests')){
            trigger_error("Unable to load Requests class", E_USER_WARNING);
            return false;
        }
        Requests::register_autoloader();

        $response = Requests::put($this->getConstant('API_URL').'/notes/add/'.$id.'.json', array(), $data, $this->options);
        $response_data = json_decode($response->body);
        return $response_data;
    }

    /**
     * @api {get} /notes/index.json
     * 
     * $notes = $this->api->getNotes();
     */
    public function getNotes($params = array()){
        if(!class_exists('Requests')){
            trigger_error("Unable to load Requests class", E_USER_WARNING);
            return false;
        }
        Requests::register_autoloader();

        $response = Requests::get($this->getConstant('API_URL').'/notes/index.json'.$this->getRequestParams($params), array(), $this->options);
        $response_data = json_decode($response->body);
        return $response_data;
    }

    /**
     * @api {delete} /notes/delete/:id.json
     * 
     * $result = $this->api->deleteNote(noteId);
     */
    public function deleteNote($id){
        if(!class_exists('Requests')){
            trigger_error("Unable to load Requests class", E_USER_WARNING);
            return false;
        }
        Requests::register_autoloader();
        $id = (int) $id;

        $response = Requests::delete($this->getConstant('API_URL').'/notes/delete/'.$id.'.json', array(), $this->options);
        $response_data = json_decode($response->body);
        return $response_data;
    }

	/**
	 * @api {get} /contacts/attributes.json
	 * 
	 * $contactAttributes = $this->api->getContactAttributes();
	 */
	public function getContactAttributes(){
		if(!class_exists('Requests')){
			trigger_error("Unable to load Requests class", E_USER_WARNING);
			return false;
		}
		Requests::register_autoloader();

		$response = Requests::get($this->getConstant('API_URL').'/contacts/attributes.json', array(), $this->options);
		$response_data = json_decode($response->body);
		return $response_data;
	}

    /************* COUNTRIES ************/

    /**
     * @api {get} /countries/index.json
     * 
     * $countries = $this->api->getCountries();
     */
    public function getCountries(){
        if(!class_exists('Requests')){
            trigger_error("Unable to load Requests class", E_USER_WARNING);
            return false;
        }
        Requests::register_autoloader();

        $response = Requests::get($this->getConstant('API_URL').'/countries/index.json', array(), $this->options);
        $response_data = json_decode($response->body);
        return $response_data;
    }

    /************ CONTACT ACITIVITES ***********/

    /**
     * @api {get} /activity/index.json
     * 
     * $contacts = $this->api->getActivities(array(
		    'contact_id' => '123',
		    'type' => 'task',
		    'limit' => 30,
		    'page' => 1
		));
     */
    public function getActivities($params = array()){
        if(!class_exists('Requests')){
            trigger_error("Unable to load Requests class", E_USER_WARNING);
            return false;
        }
        Requests::register_autoloader();

        $response = Requests::get($this->getConstant('API_URL').'/activity/index.json'.$this->getRequestParams($params), array(), $this->options);
        $response_data = json_decode($response->body);
        return $response_data;
    }

	/**
	 * @api {post} /activity/edit/:id.json
	 * 
	 * $note = $this->api->editActivity(activityId, array(
		    'contact_id' => 9,
		    'type' => 'other',
		    'name' => 'Lorem ipsum dolor',
		));
	 */
    public function editActivity($id, $data = array()){
        if(!class_exists('Requests')){
            trigger_error("Unable to load Requests class", E_USER_WARNING);
            return false;
        }
        Requests::register_autoloader();
        $id = (int) $id;

        $response = Requests::post($this->getConstant('API_URL').'/activity/edit/'.$id.'.json', array(), $data, $this->options);
        $response_data = json_decode($response->body);
        return $response_data;
    }

	/**
	 * @api {put} /activity/add/:id.json
	 * 
	 * $note = $this->api->addActivity(contactId, array(
		    'type' => 'other',
		    'name' => 'Lorem ipsum dolor',
		));
	 */
    public function addActivity($id, $data){
        if(!class_exists('Requests')){
            trigger_error("Unable to load Requests class", E_USER_WARNING);
            return false;
        }
        Requests::register_autoloader();

        $response = Requests::put($this->getConstant('API_URL').'/activity/add/'.$id.'.json', array(), $data, $this->options);
        $response_data = json_decode($response->body);
        return $response_data;
    }

    /**
     * @api {delete} /activity/delete/:id.json
     * 
     * $result = $this->api->deleteActivity(activityId);
     */
    public function deleteActivity($id){
        if(!class_exists('Requests')){
            trigger_error("Unable to load Requests class", E_USER_WARNING);
            return false;
        }
        Requests::register_autoloader();
        $id = (int) $id;

        $response = Requests::delete($this->getConstant('API_URL').'/activity/delete/'.$id.'.json', array(), $this->options);
        $response_data = json_decode($response->body);
        return $response_data;
    }


    /***************** BLACKLIST ***************/
    /**
     * @api {get} /blacklist/index.json
     * 
     * $blacklist = $this->api->getBlacklists(array(
            'type' => 'all',
            'public_number' => '44123',
            'limit' => 30,
            'page' => 1
        ));
     */
    public function getBlacklists($params = array()){
        if(!class_exists('Requests')){
            trigger_error("Unable to load Requests class", E_USER_WARNING);
            return false;
        }
        Requests::register_autoloader();

        $response = Requests::get($this->getConstant('API_URL').'/blacklist/index.json'.$this->getRequestParams($params), array(), $this->options);
        $response_data = json_decode($response->body);
        return $response_data;
    }

    /**
     * @api {delete} /blacklist/delete/:id.json
     * 
     * $result = $this->api->deleteFromBlacklist(ID);
     */
    public function deleteFromBlacklist($id){
        if(!class_exists('Requests')){
            trigger_error("Unable to load Requests class", E_USER_WARNING);
            return false;
        }
        Requests::register_autoloader();
        $id = (int) $id;

        $response = Requests::delete($this->getConstant('API_URL').'/blacklist/delete/'.$id.'.json', array(), $this->options);
        $response_data = json_decode($response->body);
        return $response_data;
    }

    /**
     * @api {put} /blacklist/add.json
     * 
     * $blacklist = $this->api->addToBlacklist(array(
            'type' => 'all',
            'public_number' => '+442012345678'
        ));
     */
    public function addToBlacklist($data){
        if(!class_exists('Requests')){
            trigger_error("Unable to load Requests class", E_USER_WARNING);
            return false;
        }
        Requests::register_autoloader();

        $response = Requests::put($this->getConstant('API_URL').'/blacklist/add.json', array(), $data, $this->options);
        $response_data = json_decode($response->body);
        return $response_data;
    }

    /**
     * @api {post} /blacklist/edit/:id.json
     * 
     * $blacklist = $this->api->editBlacklist(ID, array(
            'type' => 'all',
            'public_number' => '+442012345678'
        ));
     */
    public function editBlacklist($id, $data = array()){
        if(!class_exists('Requests')){
            trigger_error("Unable to load Requests class", E_USER_WARNING);
            return false;
        }
        Requests::register_autoloader();
        $id = (int) $id;

        $response = Requests::post($this->getConstant('API_URL').'/blacklist/edit/'.$id.'.json', array(), $data, $this->options);
        $response_data = json_decode($response->body);
        return $response_data;
    }


    /***************** TAGS ***************/

    /**
     * @api {get} /tags/index.json
     *
     * $tags = $this->api->getTags(array(
        'id' => '100',
        'limit' => 30,
        'page' => 1
        ));
     */
    public function getTags($params = array()){
        if(!class_exists('Requests')){
            trigger_error("Unable to load Requests class", E_USER_WARNING);
            return false;
        }
        Requests::register_autoloader();

        $response = Requests::get($this->getConstant('API_URL').'/tags/index.json'.$this->getRequestParams($params), array(), $this->options);
        $response_data = json_decode($response->body);
        return $response_data;
    }

    /**
     * @api {put} /tags/add.json
     *
     * $result = $this->api->addTag(array(
            'name' => 'VIP'
        ));
     */
    public function addTag($data){
        if(!class_exists('Requests')){
            trigger_error("Unable to load Requests class", E_USER_WARNING);
            return false;
        }
        Requests::register_autoloader();

        $response = Requests::put($this->getConstant('API_URL').'/tags/add.json', array(), $data, $this->options);
        $response_data = json_decode($response->body);
        return $response_data;
    }

    /**
     * @api {post} /tags/edit/:id.json
     *
     * $result = $this->api->editTag(tagId, array(
            'name' => 'Tag',
    ));
     */
    public function editTag($id, $data = array()){
        if(!class_exists('Requests')){
            trigger_error("Unable to load Requests class", E_USER_WARNING);
            return false;
        }
        Requests::register_autoloader();
        $id = (int) $id;

        $response = Requests::post($this->getConstant('API_URL').'/tags/edit/'.$id.'.json', array(), $data, $this->options);
        $response_data = json_decode($response->body);
        return $response_data;
    }

    /**
     * @api {delete} /tags/delete/:id.json
     *
     * $result = $this->api->deleteTag(tagId);
     */
    public function deleteTag($id){
        if(!class_exists('Requests')){
            trigger_error("Unable to load Requests class", E_USER_WARNING);
            return false;
        }
        Requests::register_autoloader();
        $id = (int) $id;

        $response = Requests::delete($this->getConstant('API_URL').'/tags/delete/'.$id.'.json', array(), $this->options);
        $response_data = json_decode($response->body);
        return $response_data;
    }

    /***************** CAMPAIGNS ***************/

    /**
     * @api {get} /campaigns/index.json
     *
     * $tags = $this->api->getCampaigns(array(
            'id' => '100',
            'limit' => 30,
            'page' => 1
        ));
     */
    public function getCampaigns($params = array()){
        if(!class_exists('Requests')){
            trigger_error("Unable to load Requests class", E_USER_WARNING);
            return false;
        }
        Requests::register_autoloader();

        $response = Requests::get($this->getConstant('API_URL').'/campaigns/index.json'.$this->getRequestParams($params), array(), $this->options);
        $response_data = json_decode($response->body);
        return $response_data;
    }

    /**
     * @api {put} /campaigns/add.json
     *
     * $result = $this->api->addCampaign(array(
    'name' => 'VIP'
    ));
     */
    public function addCampaign($data){
        if(!class_exists('Requests')){
            trigger_error("Unable to load Requests class", E_USER_WARNING);
            return false;
        }
        Requests::register_autoloader();

        $response = Requests::put($this->getConstant('API_URL').'/campaigns/add.json', array(), $data, $this->options);
        $response_data = json_decode($response->body);
        return $response_data;
    }

    /**
     * @api {post} /campaigns/edit/:id.json
     *
     * $result = $this->api->editCampaign(campaignId, array(
        'name' => 'Campaign name',
        ...
    ));
     */
    public function editCampaign($id, $data = array()){
        if(!class_exists('Requests')){
            trigger_error("Unable to load Requests class", E_USER_WARNING);
            return false;
        }
        Requests::register_autoloader();
        $id = (int) $id;

        $response = Requests::post($this->getConstant('API_URL').'/campaigns/edit/'.$id.'.json', array(), $data, $this->options);
        $response_data = json_decode($response->body);
        return $response_data;
    }

    /**
     * @api {delete} /campaigns/delete/:id.json
     *
     * $result = $this->api->deleteCampaign(campaignId);
     */
    public function deleteCampaign($id){
        if(!class_exists('Requests')){
            trigger_error("Unable to load Requests class", E_USER_WARNING);
            return false;
        }
        Requests::register_autoloader();
        $id = (int) $id;

        $response = Requests::delete($this->getConstant('API_URL').'/campaigns/delete/'.$id.'.json', array(), $this->options);
        $response_data = json_decode($response->body);
        return $response_data;
    }

    /**
     * @api {post} /bulks/contacts.json
     *
     * $result = $this->api->bulkContacts(array(
      [
        'action' => 'add_contact',
        ...
      ]
    ));
     */
    public function bulkContacts($data){
        if(!class_exists('Requests')){
            trigger_error("Unable to load Requests class", E_USER_WARNING);
            return false;
        }
        Requests::register_autoloader();

        $response = Requests::post($this->getConstant('API_URL').'/bulks/contacts.json', array(), $data, $this->options);
        $response_data = json_decode($response->body);
        return $response_data;
    }
}
