<?php

class dbUsers extends Zend_Db_Table_Abstract
{
    protected $_name = 'users';

    public function checkLogin($username, $password)
    {
        $where = $this->getAdapter()->quoteInto('username LIKE ?', $username);
        $where .= 'AND ' . $this->getAdapter()->quoteInto('password LIKE ?', $password);
        return $this->fetchRow($where);
    }

    public function checkUsername($username)
    {
        $where = $this->getAdapter()->quoteInto('username LIKE ?', $username);
        return $this->fetchRow($where);
    }
}

class dbRoomType extends Zend_Db_Table_Abstract
{
    protected $_name = 'room_type';
}

class dbRoom extends Zend_Db_Table_Abstract
{
    protected $_name = 'room';
}

class dbArea extends Zend_Db_Table_Abstract
{
    protected $_name = 'area';
}

class dbStudent extends Zend_Db_Table_Abstract
{
    protected $_name = 'student';

    public function getByCode($code)
    {
        $where = $this->getAdapter()->quoteInto('code LIKE ?', $code);
        return $this->fetchRow($where);
    }
}

class dbStudentInRoom extends Zend_Db_Table_Abstract
{
    protected $_name = 'student_in_room';

    public function checkStudentInRoom($id)
    {
        $where = $this->getAdapter()->quoteInto('id = ?', $id);
        //$where .= ' AND active = 1';
        return $this->fetchRow($where);
    }

    public function checkSIRoom($id)
    {
        $where = $this->getAdapter()->quoteInto('student_id = ?', $id);
        $where .= ' AND active = 1';
        return $this->fetchRow($where);
    }

    public function findLastRoom($student_id)
    {
        $where = $this->getAdapter()->quoteInto('student_id = ?', $student_id) . ' and ' . $this->getAdapter()->quoteInto('school_year like ?', '2012-2013') . ' and room_id > 179';

        return $this->fetchRow($where, 'date_in DESC');
    }
}

class dbStudentPayment extends Zend_Db_Table_Abstract
{
    protected $_name = 'student_payedfee_history';

    public function checkStudentPayment($student_in_room_id)
    {
        $where = $this->getAdapter()->quoteInto('student_in_room_id = ?', $student_in_room_id);
        $order = 'date_payment asc';
        return $this->fetchAll($where, $order);
    }

    //public function findLastPayment($year, $period)
    //{
        //$where = $this->getAdapter()->quoteInto('student_in_room_id = ?', $student_in_room_id);
        //$order = 'date_payment asc';
    //}
}