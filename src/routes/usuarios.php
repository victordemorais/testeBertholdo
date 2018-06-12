<?php
$this->get('/all', function ($request,$response, $args) {
    try{
        $sql = "SELECT * FROM bertholdo_users";
        $sth = $this->db->prepare($sql);
        $sth->execute();
        $count = $sth->rowCount(); //verifica se veio algo            
        if ($count > 0){
            $response = array(); //Resultado
            while ($row = $sth->fetch(\PDO::FETCH_ASSOC)) {        
                $tempResult = array(); 
                $tempResult['id'] = floatval($row['id']);
                $tempResult['nome'] = utf8_encode($row['nome']);
                $tempResult['email'] = utf8_encode($row['email']);
                $tempResult['senha'] = utf8_encode($row['senha']);
                $tempResult['data_cadastro'] = date('d/m/Y',strtotime($row['data_cadastro']));
                array_push($response,$tempResult);
            }
            return $this->response->withStatus(200)->withJson($response); 
        } else {
            return $this->response->withStatus(200)->withJson("Não foi encontrado nenhum resultado");  
        }

    }
    catch(\Exception $ex){

        return $response->withJson(array('error' => $ex->getMessage()),422);
    }
    
});

$this->get('/[{id}]', function ($request,$response, $args) {
    try{
        $sql = "SELECT * FROM bertholdo_users WHERE id = :id";
        $sth = $this->db->prepare($sql);
        $query = ($args['id']);
        $sth->bindParam("id", $query);						
        $sth->execute();

        $count = $sth->rowCount(); //verifica se veio algo            
        if ($count > 0){
            $response = array(); //esse vai ser teu resultado
            while ($row = $sth->fetch(\PDO::FETCH_ASSOC)) {             
               
                $response['id'] = floatval($row['id']);
                $response['nome'] = utf8_encode($row['nome']);
                $response['email'] = utf8_encode($row['email']);
                $response['senha'] = utf8_encode($row['senha']);
                $response['data_cadastro'] = date('d/m/Y',strtotime($row['data_cadastro']));
                
            }

            return $this->response->withStatus(200)->withJson($response); 
        } else {
            return $this->response->withStatus(200)->withJson('Não foi encontrado o usuário desejado.');  
        }

       
    }
    catch(\Exception $ex){
        return $response->withJson(array('error' => $ex->getMessage()),422);
    }
    
 });

 $this->post('/', function ($request, $response) {
    
    try {
        $sql = "INSERT INTO bertholdo_users(nome,email,senha,data_cadastro) values (:nome,:email,:senha,:data_cadastro)";
        $sth = $this->db->prepare($sql);
       
        $input = $request->getParsedBody(); //pega o JSON que veio do post
        $hashpassword = password_hash($input['senha'],PASSWORD_DEFAULT);
        $dataatual =  date("Y-m-d");
         
        $sth->bindParam("nome", $input['nome']);						
        $sth->bindParam("email", $input['email']);						
        $sth->bindParam("senha",$hashpassword);						
        $sth->bindParam("data_cadastro",$dataatual);								
        $sth->execute();
        $count = $sth->rowCount();     
        if($count > 0){
            return $response->withJson("Usuário Criado com Sucesso ID:".$this->db->lastInsertId(),200);
        }else{
            return $response->withJson("Usuário não encontrado",422);
        }
    }
    catch(\Exception $ex){
          return $response->withJson(array('error' => $ex->getMessage()),422);
    }
});

$this->put('/[{id}]', function ($request, $response,$args) {
        
    try {
        $sql = "update bertholdo_users set nome=:nome, email=:email,senha=:senha where id = :id";
        $sth = $this->db->prepare($sql);
        $query = ($args['id']);
        $sth->bindParam("id", $query);		
        $input = $request->getParsedBody(); //pega o JSON que veio do post
        $hashpassword = password_hash($input['senha'],PASSWORD_DEFAULT);
        
        $sth->bindParam("nome", $input['nome']);						
        $sth->bindParam("email", $input['email']);						
        $sth->bindParam("senha",$hashpassword);						
        $sth->execute();
        
        $count = $sth->rowCount();     
        if($count > 0){
            return $response->withJson("Usuário editado com sucesso.",200);
        }else{
            return $response->withJson("Usuário não encontrado",422);
        }
    } catch (\Exception $ex) {
        return $this->response->withStatus(500)->write($ex->getMessage());
    }
});


$this->delete('/[{id}]', function ($request,$response,$args) {
    try{
        $sql = "DELETE FROM bertholdo_users WHERE id = :id";
        $sth = $this->db->prepare($sql);
        $query = ($args["id"]);
        $sth->bindParam("id", $query);		
        
        $result = $sth->execute();
        $count = $sth->rowCount();     
        if($count > 0){
            return $response->withJson("Usuário Deletado com sucesso.",200);
        }else{
            return $response->withJson("Usuário não encontrado",422);
        }
    }
    catch(\Exception $ex){
        return $response->withJson(array('error' => $ex->getMessage()),500);
    }
    
 });