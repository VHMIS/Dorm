<?php

if($sessionLoginError)
{
    echo '-99999';
    exit();
}

if($_ssvUri['params']['action'] == 'update')
{
    $id = $rg->GetVar($_POST['id'], '');
    $name = $rg->GetVar($_POST['name'], '', true);
    $maxs = $rg->GetVar($_POST['maxs'], '');
    $feed = $rg->GetVar($_POST['feed'], '');
    $feem = $rg->GetVar($_POST['feem'], '');

    if($id == '' || $name == '' || $maxs == '' || $feed == '' || $feem == '')
    {
        echo '-88888';
        exit();
    }

    if(!$validate->isDigit($id) || !$validate->isDigit($maxs) || !$validate->isDigit($feed) || !$validate->isDigit($feem))
    {
        echo '-77777';
        exit();
    }

    //Get row
    if($rows = $dbRoomType->find($id))
    {
        $rows = $rows[0];
        $rows->name = $name;
        $rows->max_students = $maxs;
        $rows->fee_day = $feed;
        $rows->fee_month = $feem;
        $rows->save();
        echo 'ok';
        exit();
    }
    else
    {
        echo '-666666';
        exit();
    }
}
elseif($_ssvUri['params']['action'] == 'new')
{
    $name = $rg->GetVar($_POST['name'], '', true);
    $maxs = $rg->GetVar($_POST['maxs'], '');
    $feed = $rg->GetVar($_POST['feed'], '');
    $feem = $rg->GetVar($_POST['feem'], '');

    if($name == '' || $maxs == '' || $feed == '' || $feem == '')
    {
        echo '-88888';
        exit();
    }

    if(!$validate->isDigit($maxs) || !$validate->isDigit($feed) || !$validate->isDigit($feem))
    {
        echo '-77777';
        exit();
    }

    $row = $dbRoomType->fetchNew();

    $row->name = $name;
    $row->max_students = $maxs;
    $row->fee_day = $feed;
    $row->fee_month = $feem;

    $row->save();
    echo $row->id;
    exit();
}