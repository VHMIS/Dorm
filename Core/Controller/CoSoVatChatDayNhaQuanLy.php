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

    if($id == '' || $name == '')
    {
        echo '-88888';
        exit();
    }

    if(!$validate->isDigit($id))
    {
        echo '-77777';
        exit();
    }

    //Get row
    if($rows = $dbArea->find($id))
    {
        $rows = $rows[0];
        $rows->name = $name;
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

    if($name == '')
    {
        echo '-88888';
        exit();
    }

    $row = $dbArea->fetchNew();

    $row->name = $name;

    $row->save();
    echo $row->id;
    exit();
}