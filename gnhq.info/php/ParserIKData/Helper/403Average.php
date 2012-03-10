<?php
class ParserIKData_Helper_403Average
{
    private $_projectCodes = null;

    private $_usedRegionNums = array();

    private $_totalPopulation = 0;

    private $_protoGateway = null;

    private $_protoOfGateway = null;

    private $_regionGateway = null;

    private $_projectUikCount = 0;

    private $_projectDiagramData = array();

    private $_ofUikCount = 0;

    private $_ofDiagramData = array();

    private $_controlRelTrue = true;

    private $_averageByUik = true;

    public function __construct($projectCodes, $controlRelTrue, $averageByUik)
    {
        $this->_projectCodes = $projectCodes;
        $this->_controlRelTrue = $controlRelTrue;
        $this->_averageByUik = $averageByUik;
    }

    /**
     * @return ParserIKData_Helper_403Average
     */
    public function calcProjectResults()
    {
        $data = array();
        $regions = $this->_getRegionGateway()->getAll();
        foreach ($regions as $region) {
            /* @var $region ParserIKData_Model_Region */
            $result = $this->_getProtoGateway()->getMixedResult(
                    $region->getRegionNum(),
                    null,
                    null,
                    null,
                    $this->_projectCodes,
                    $this->_controlRelTrue,
                    $this->_averageByUik
            );
            $regData =  $result->getDiagramData(true, 2);
            $uikCount = $result->getUikCount();
            $this->_projectUikCount += $uikCount;

            if ($uikCount != 0) {
                $this->_totalPopulation += $region->getPopulation();
                $data[$region->getRegionNum()] = $regData;
                $this->_usedRegionNums[] = $region->getRegionNum();
            }
        }

        foreach ($regions as $region) {
            if (!isset($data[$region->getRegionNum()])) {
                continue;
            }
            $regionData = $data[$region->getRegionNum()];
            foreach ($regionData as $ind => $val) {
                if (!isset($this->_projectDiagramData[$ind])) {
                    $this->_projectDiagramData[$ind] = 0;
                }
                $this->_projectDiagramData[$ind] += round($val * $region->getPopulation()/$this->_totalPopulation, 2);
            }
        }
        return $this;
    }


    /**
     * @return ParserIKData_Helper_403Average
     */
    public function calcOfResults()
    {
        $result = $this->_getProtoOfGateway()->getMixedResult(
                $this->_usedRegionNums,
                null,
                null,
                null,
                ParserIKData_Model_Protocol403::TYPE_OF,
                $this->_controlRelTrue,
                $this->_averageByUik
        );
        $this->_ofDiagramData = $result->getDiagramData(true, 2);
        $this->_ofUikCount = $result->getUikCount();
        return $this;
    }

    public function getProjectDiagramData()
    {
        return $this->_projectDiagramData;
    }

    public function getProjectUikCount()
    {
        return $this->_projectUikCount;
    }

    public function getOfDiagramData()
    {
        return $this->_ofDiagramData;
    }

    public function getOfUikCount()
    {
        return $this->_ofUikCount;
    }

    public function getRegionNums()
    {
        return $this->_usedRegionNums;
    }


    /**
     * @return ParserIKData_Gateway_Protocol403
     */
    private function _getProtoOfGateway() {
        if (!$this->_protoOfGateway) {
            $this->_protoOfGateway = new ParserIKData_Gateway_Protocol403Offile();
            $this->_protoOfGateway->setUseCache(true);
        }
        return $this->_protoOfGateway;
    }

    /**
     * @return ParserIKData_Gateway_Protocol403
     */
    private function _getProtoGateway() {
        if (!$this->_protoGateway) {
            $this->_protoGateway = new ParserIKData_Gateway_Protocol403();
            $this->_protoGateway->setUseCache(true);
        }
        return $this->_protoGateway;
    }

    /**
     * @return ParserIKData_Gateway_Region
     */
    private function _getRegionGateway() {
        if (!$this->_regionGateway) {
            $this->_regionGateway = new ParserIKData_Gateway_Region();
        }
        return $this->_regionGateway;
    }
}