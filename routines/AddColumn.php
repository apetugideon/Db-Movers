<?php
interface AddColumn {
    public function add($name, $size="");
}


class AddString implements AddColumn {
    public function add($name, $size="") {
        return ($size != "") ? "`{$name}` VARCHAR({$size})" : "`{$name}` VARCHAR";
    }
}


class AddInt implements AddColumn {
    public function add($name, $size="") {
        return ($size != "") ? "`{$name}` INT({$size})" : "`{$name}` INT";
    }
}


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


class AddChar implements AddColumn {
    public function add($name, $size="") {
        return ($size != "") ? "`{$name}` CHAR({$size})" : "`{$name}` CHAR";
    }
}


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