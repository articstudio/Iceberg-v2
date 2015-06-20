<?php
namespace Iceberg\Abstracts;

abstract class ObjectDatabaseRelation extends \Iceberg\Abstracts\ObjectDatabase
{
    protected static $DB_PARENTS = [];
    protected static $DB_CHILDS = [];
}