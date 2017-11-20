<?php

/*
 * To change this license
 */

namespace Application\Entity\Repository;

/**
 * Description of EnviadoRepository
 *
 * @author Paulo Watakabe <email>watakabe05@gmail.com</email>
 */
class EnviadoRepository extends AbstractRepository {

    /**
     * Procura no sistema novas mensagem para o usuario do sistema.
     * Se houver novas mensagens devolve para o o navegador
     * E marcar essas mensagens como lida para não enviar novamente
     * 
     * @param array $user
     * @return array
     */
    public function getMsgNotReceived(array $user) {
        $em = $this->getEntityManager();
        /* @var $receive \Application\Entity\Enviado */

        $user = $em->getReference("\Application\Entity\User", $user['idUsuario']);

        $receives = $this->findBy([
            'toUser' => $user->getId(),
            'dateRecebido' => $this->strToDate('15/01/1950 12:01:00')
        ]);
        echo count($receives);
        die;

        if (empty($receives)) {
            return [];
        }

        foreach ($receives as $receive) {
            $receive->setDateRecebido(new \DateTime('now'));
            $em->persist($receive);
        }
        $em->flush();

        return $receives;
    }

    public function getEnviadoDql($where, array $parameters, $orderBy = 'e.dateEnviado') {
        // Monta a dql para fazer consulta no BD Cuidado com relacionamentos de zeros para muitos (ex: Grupos)
        $qb = $this->getEntityManager()
                ->createQueryBuilder()
                ->from('Application\Entity\Enviado', 'e')
                ->select('e, m, u, tu')
                ->join('e.mensagemMensagem', 'm')
                ->join('e.fromUser', 'u')
                ->join('e.toUser', 'tu')
                ->where($where)
                ->setParameters($parameters)
                ->orderBy($orderBy);

        return $qb->getQuery()->getResult();
    }

    /**
     * Retorna todos os registros baseados na data de envio da mensagem além dos
     * usuários/grupos de origem/destino
     * 
     * @author Danilo Dorotheu <danilo.dorotheu@live.com>
     * @since 01-06-2016
     * @param array $filters
     * @return type
     */
    public function getEnviados(array $filters = array()) {
        $query = $this->getEntityManager()
                ->createQueryBuilder()
                ->select('e')
                ->from('Application\Entity\Enviado', 'e');

        $where = 'e.dateEnviado > :dateEnviado';
        if ("usuario" == $filters['tipoContato']) {
            $where .= ' AND ((e.fromUser = :from AND e.toUser = :idUser AND e.toGrupo IS NULL)'; //Enviadas para mim
            $where .= ' OR  (e.fromUser = :idUser AND e.toUser = :from AND e.toGrupo IS NULL))'; //Enviadas por mim
        } else {
            $where .= ' AND ((e.toUser = :idUser AND e.toGrupo = :from)';
            $where .= ' OR  (e.fromUser = :idUser AND e.toGrupo = :from ))';
        }
        unset($filters['tipoContato']);

        $query->where($where)
                ->setParameters($filters)
                ->orderBy('e.dateEnviado');
        
        return $query->getQuery()->getResult();
    }

}
