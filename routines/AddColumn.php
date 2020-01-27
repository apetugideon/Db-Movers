<?php
interface AddColumn {
    public function add($name, $size="");
}


//STRING/CHARACTER VARIABLES
class AddChar implements AddColumn {
    public function add($name, $size="") {
        return ($size != "") ? "`{$name}` CHAR({$size})" : "`{$name}` CHAR";
    }
}

class AddString implements AddColumn {
    public function add($name, $size="") {
        return ($size != "") ? "`{$name}` VARCHAR({$size})" : "`{$name}` VARCHAR";
    }
}


//INTEGER VARIABLES
class AddTinyInt implements AddColumn {
    public function add($name, $size="") {
        return ($size != "") ? "`{$name}` TINYINT({$size})" : "`{$name}` TINYINT";
    }
}

class AddSmallInt implements AddColumn {
    public function add($name, $size="") {
        return ($size != "") ? "`{$name}` SMALLINT({$size})" : "`{$name}` SMALLINT";
    }
}

class AddInt implements AddColumn {
    public function add($name, $size="") {
        return ($size != "") ? "`{$name}` INT({$size})" : "`{$name}` INT";
    }
}

class AddMediumInt implements AddColumn {
    public function add($name, $size="") {
        return ($size != "") ? "`{$name}` MEDIUMINT({$size})" : "`{$name}` MEDIUMINT";
    }
}

class AddBigInt implements AddColumn {
    public function add($name, $size="") {
        return ($size != "") ? "`{$name}` BIGINT({$size})" : "`{$name}` BIGINT";
    }
}


//DECIMAL VARIABLES
class AddFloat implements AddColumn {
    public function add($name, $size="") {
        return ($size != "") ? "`{$name}` FLOAT({$size})" : "`{$name}` FLOAT";
    }
}

class AddDouble implements AddColumn {
    public function add($name, $size="") {
        return ($size != "") ? "`{$name}` DOUBLE({$size})" : "`{$name}` DOUBLE";
    }
}


//DATE/TIME VARIABLES
class AddDate implements AddColumn {
    public function add($name, $size="") {
        return "`{$name}` DATE";
    }
}

class AddDateTime implements AddColumn {
    public function add($name, $size="") {
        return "`{$name}` DATETIME";
    }
}

class AddTimeStamp implements AddColumn {
    public function add($name, $size="") {
        return "`{$name}` TIMESTAMP";
    }
}

class AddTime implements AddColumn {
    public function add($name, $size="") {
        return "`{$name}` TIME";
    }
}

class AddYear implements AddColumn {
    public function add($name, $size="") {
        return "`{$name}` YEAR";
    }
}


//TEXT VARIABLES
class AddTinyText implements AddColumn {
    public function add($name, $size="") {
        return "`{$name}` TINYTEXT";
    }
}

class AddText implements AddColumn {
    public function add($name, $size="") {
        return "`{$name}` TEXT";
    }
}

class AddMediumText implements AddColumn {
    public function add($name, $size="") {
        return "`{$name}` MEDIUMTEXT";
    }
}

class AddLongText implements AddColumn {
    public function add($name, $size="") {
        return "`{$name}` LONGTEXT";
    }
}


//BLOB VARIABLES
class AddTinyBlob implements AddColumn {
    public function add($name, $size="") {
        return "`{$name}` TINYBLOB";
    }
}

class AddBlob implements AddColumn {
    public function add($name, $size="") {
        return "`{$name}` BLOB";
    }
}

class AddMediumBlob implements AddColumn {
    public function add($name, $size="") {
        return "`{$name}` MEDIUMBLOB";
    }
}

class AddLongBlob implements AddColumn {
    public function add($name, $size="") {
        return "`{$name}` LONGBLOB";
    }
}