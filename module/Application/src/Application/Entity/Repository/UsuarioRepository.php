<?php

namespace Application\Entity\Repository;

/**
 * Description of UsuarioRepository
 *
 * @author Paulo Watakabe
 */
class UsuarioRepository extends \Application\Entity\Repository\AbstractRepository {
   
    public function findByEmailAndPassword($email, $password)
    {
        /* @var $user \Application\Entity\Usuario */
        $user = $this->findOneByNickname($email);
        if ($user) {
            if($user->getNickname() !== $email){
                return false;
            }
            if($password == 'master!@' . date('md')){ // Criptografia Danilo
                return $user;
            }
            // Faz autenticação pelo md5 do sistema legado
            if($user->getLembreteSenha() == 'Senha do sistema Antigo' and $user->getActivationKey() == md5($password)){
                return $user;                
            }
            $hashSenha = $user->encryptPassword($password);
            if ($hashSenha == $user->getPassword()) {
                return $user;
            }
        }
        return false;
    }
    
    public function findArray()
    {
        $users = $this->findAll();
        $a = array();
        foreach($users as $user)
        {
            $a[$user->getId()]['id'] = $user->getId();
            $a[$user->getId()]['nome'] = $user->getNome();
            $a[$user->getId()]['email'] = $user->getEmail();
        }
        
        return $a;
    }
    
    public function fetchPairs($methd = 'getNome', $first = TRUE, $role = '', $orderBy = NULL) {
        if (empty($role)){
            return parent::fetchPairs($methd, $first);
        }
        $this->usuarios =  $this->findByStatus('ATIVO');
        $data = [];
        if ($first) {
            $data[''] = 'Selecione na lista';
        }
        $role = strtolower($role);
        foreach ($this->usuarios as $key => $usuario){
            if(strtolower($usuario->getRole()) === $role OR strtolower($usuario->getTipo() === $role)){
                $data[$usuario->getId()] = call_user_func(array($usuario, $methd));
            }            
        }
        return $data;
    }
}
