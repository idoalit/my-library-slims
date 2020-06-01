<?php
/**
 * @Created by          : Waris Agung Widodo (ido.alit@gmail.com)
 * @Date                : 23/05/20 09.05
 * @File name           : Receipt.php
 */

namespace Klaras\Message;

class Receipt implements \JsonSerializable
{
    protected $member_id;
    protected $member_name;
    protected $loans = [];
    protected $extends = [];
    protected $returns = [];
    protected $fines;
    protected $staff;

    /**
     * @return mixed
     */
    public function getMemberId()
    {
        return $this->member_id;
    }

    /**
     * @param mixed $member_id
     * @return Receipt
     */
    public function setMemberId($member_id)
    {
        $this->member_id = $member_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMemberName()
    {
        return $this->member_name;
    }

    /**
     * @param mixed $member_name
     * @return Receipt
     */
    public function setMemberName($member_name)
    {
        $this->member_name = $member_name;
        return $this;
    }

    /**
     * @return array
     */
    public function getLoans(): array
    {
        return $this->loans;
    }

    /**
     * @param array $loans
     * @return Receipt
     */
    public function setLoans(array $loans): Receipt
    {
        $this->loans = $loans;
        return $this;
    }

    public function addLoanItem(Item $item): Receipt
    {
        $this->loans[] = $item;
        return $this;
    }

    /**
     * @return array
     */
    public function getExtends(): array
    {
        return $this->extends;
    }

    /**
     * @param array $extends
     * @return Receipt
     */
    public function setExtends(array $extends): Receipt
    {
        $this->extends = $extends;
        return $this;
    }

    public function addExtendItem(Item $item): Receipt
    {
        $this->extends[] = $item;
        return $this;
    }

    /**
     * @return array
     */
    public function getReturns(): array
    {
        return $this->returns;
    }

    /**
     * @param array $returns
     * @return Receipt
     */
    public function setReturns(array $returns): Receipt
    {
        $this->returns = $returns;
        return $this;
    }

    public function addReturnItem(Item $item): Receipt
    {
        $this->returns[] = $item;
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
     * @return Receipt
     */
    public function setFines($fines)
    {
        $this->fines = $fines;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStaff()
    {
        return $this->staff;
    }

    /**
     * @param mixed $staff
     * @return Receipt
     */
    public function setStaff($staff)
    {
        $this->staff = $staff;
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