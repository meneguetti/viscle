<?php

namespace Viscle;

/**
 * Description of Parser
 *
 * @author Vitor Meneguetti
 */
class Parser
{
    //columns of "xt" file (xdebug trace)
    public const OBJECT_ID_COL = 0;
    public const FUNCTION_ID_COL = 1;
    public const STATUS_COL = 2;
    public const NAME_COL = 5;
    //status of a function (0 opened - 1 closed)
    public const STATUS_OPENED = 0;
    public const STATUS_CLOSED = 1;

    /**
     * It parses the data within the file and returns a json with the data parsed
     * 
     * @param string $filename
     * @return string 
     */
    public function parse(string $filename)
    {
        //prepare raw json to render the graph with nodes
        $lifecycle = $this->parseToJson($filename);

        //filter the raw object to let the graph cleaner
        $lifecycle = json_encode(array_values($this->filter($lifecycle)));

        return $lifecycle;
    }

    protected function parseToJson(string $filename)
    {

        $rows = file($filename);

        //nodes stack
        $nodesStack = [];

        //first node
        $node = [];
        $node['name'] = '';
        $node['children'] = [];

        //push node to stack
        array_push($nodesStack, $node);

        foreach ($rows as $row) {
            $rowExploded = explode("\t", $row);

            //verify if it's a worth trace line to parse
            if (!$this->isValidLine($rowExploded, count($nodesStack), !empty($nodesStack[0]['children']))) {
                continue;
            }

            //STATUS OPENED => it means new node
            if ($rowExploded[self::STATUS_COL] == self::STATUS_OPENED) {

                $linkName = '';
                $objMethodExploded = explode('->', $rowExploded[self::NAME_COL]);
                //define the function name to be shown in link between nodes
                if (count($objMethodExploded) === 2) {
                    $rowExploded[self::NAME_COL] = $objMethodExploded[0];
                    $linkName = $objMethodExploded[1];
                }

                $node = [];
                $node['name'] = $rowExploded[self::NAME_COL];
                $node['link'] = $linkName;
                $node['children'] = [];
            }
            //STATUS CLOSED => it means end of the node
            else {
                $lastNode = array_pop($nodesStack);
                $node = array_pop($nodesStack);
                if ($lastNode['name'] === $node['name']) {
                    $lastNode['name'] = '';
                }
                $node['children'][] = $lastNode;
            }

            //push to index stack
            array_push($nodesStack, $node);
        }

        //if the number of opened and closed objects/functions doesn't exact match
        if (count($nodesStack) > 1) {
            $nodesStack = $this->fixUnclosedNodes($nodesStack);
        }

        return $nodesStack;
    }

    protected function fixUnclosedNodes($nodesStack)
    {

        $lastNode = array_pop($nodesStack);
        $node = array_pop($nodesStack);
        $node ['children'][] = $lastNode;

        //push to index stack
        array_push($nodesStack, $node);

        if (count($nodesStack) > 1) {
            $nodesStack = $this->fixUnclosedNodes($nodesStack);
        }

        return $nodesStack;
    }

    protected function isValidLine(array $rowExploded, int $stackQtyNodes, bool $hasChildren)
    {
        //not valid if
        // => less then 4 columns        
        if (count($rowExploded) < 4) {
            return false;
        }
        // => just root node, no children and status of current line is closed
        elseif ($stackQtyNodes === 1 && !$hasChildren && $rowExploded[self::STATUS_COL] == self::STATUS_CLOSED) {
            return false;
        }
        // => obj column is empty
        elseif (empty($rowExploded[self::OBJECT_ID_COL])) {
            return false;
        }
        // => function name is xdebug_stop_trace
        elseif (isset($rowExploded[self::NAME_COL]) && $rowExploded[self::NAME_COL] === 'xdebug_stop_trace') {
            return false;
        }
        // => Class/function name is Viscle\Viscle::render
        elseif (isset($rowExploded[self::NAME_COL]) && $rowExploded[self::NAME_COL] === 'Viscle\Viscle::render') {
            return false;
        }

        return true;
    }

    protected function filter(array $lifecycleDecoded)
    {
        $childrenControl = [];

        //verify which children should be removed from nodes
        foreach ($lifecycleDecoded as $index => $item) {

            //if this item has no children
            //then marks it to be removed if necessary
            if (empty($item['children'])) {
                $childrenControl[$item['name']]['empty_children'][] = $index;
            }
            //else marks this node has children
            else {
                $childrenControl[$item['name']]['has_children'] = true;
                $lifecycleDecoded[$index]['children'] = $this->filter($item['children']);
            }
        }

        //remove some selected children from nodes, if necessary...
        foreach ($childrenControl as $childControl) {

            $lengthEmptyChildren = isset($childControl['empty_children']) ? count($childControl['empty_children']) : 0;

            if ($lengthEmptyChildren > 0) {
                //if has children is set
                //then remove all children from node                
                if (isset($childControl['has_children'])) {
                    foreach ($childControl['empty_children'] as $childToRemove) {
                        unset($lifecycleDecoded[$childToRemove]);
                    }
                }
                //else remove all children from node BUT firstborn
                else {
                    for ($i = 1; $i < $lengthEmptyChildren; $i++) {
                        unset($lifecycleDecoded[$childControl['empty_children'][$i]]);
                    }
                }
            }
        }

        return array_values($lifecycleDecoded);
    }

}
