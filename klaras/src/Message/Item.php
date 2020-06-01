<?php
/**
 * @Created by          : Waris Agung Widodo (ido.alit@gmail.com)
 * @Date                : 23/05/20 09.07
 * @File name           : Item.php
 */

namespace Klaras\Message;


class Item implements \JsonSerializable
{
    protected $item_code;
    protected $title;
    protected $loan_date;
    protected $due_date;
    protected $return_date;
    protected $extend_date;
    protected $renewed;
    protected $fines;

    /**
     * @return mixed
     */
    public function getItemCode()
    {
        return $this->item_code;
    }

    /**
     * @param mixed $item_code
     * @return Item
     */
    public function setItemCode($item_code)
    {
        $this->item_code = $item_code;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     * @return Item
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLoanDate()
    {
        return $this->loan_date;
    }

    /**
     * @param mixed $loan_date
     * @return Item
     */
    public function setLoanDate($loan_date)
    {
        $this->loan_date = $loan_date;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDueDate()
    {
        return $this->due_date;
    }

    /**
     * @param mixed $due_date
     * @return Item
     */
    public function setDueDate($due_date)
    {
        $this->due_date = $due_date;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getReturnDate()
    {
        return $this->return_date;
    }

    /**
     * @param mixed $return_date
     * @return Item
     */
    public function setReturnDate($return_date)
    {
        $this->return_date = $return_date;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getExtendDate()
    {
        return $this->extend_date;
    }

    /**
     * @param mixed $extend_date
     * @return Item
     */
    public function setExtendDate($extend_date)
    {
        $this->extend_date = $extend_date;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRenewed()
    {
        return $this->renewed;
    }

    /**
     * @param mixed $renewed
     * @return Item
     */
    public function setRenewed($renewed)
    {
        $this->renewed = $renewed;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFines()
    {
        return $this->fines;
    }

    /**
     * @param mixed $fines
     * @return Item
     */
    public function setFines($fines)
    {
        $this->fines = $fines;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}