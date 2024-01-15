<?php
$lenghtMdp = 5;
$lenghtId = 10;

/**
 * verif_age
 *
 * @param  string $s
 * @return bool
 */
function verif_age(string $s) : bool {
    $dateInput=new DateTime($s);
    $dateNow=new DateTime();
    $diff= $dateNow->diff($dateInput);
    if ($diff->y > 18 || ($diff->y === 18 && $diff->m > 0) || ($diff->y === 18 && $diff->m === 0 && $diff->d >= 0)) {
        return true;
    }
    else {
        return false;
    }
}

/**
 * @param  int $lenght the desired password lenght
 * @return string the password generated
 * 
 * a function that generates a random password of letters and numbers, of size "lenght"
 * 
 */
function generateRandomPassword(int $lenght) : string{
    // The base in which we will pick elements
    $base ='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $specialCarac = '%&$@#';

    //generate the paswword, a random size without special caracters, we will have a maximum of 2 special caraters in the password
    $password = '';
    $nonSpecialCarac = random_int($lenght-2, $lenght);
    for($i=0; $i<$nonSpecialCarac; $i++){
        $randomIndex = random_int(0, strlen($base)-1);
        $password .= $base[$randomIndex];
    }

    //adding the special caracters
    for($i=0; $i<($lenght-$nonSpecialCarac); $i++){
        $randomIndex = random_int(0, strlen($specialCarac)-1);
        $password .= $specialCarac[$randomIndex];
    }

    //a last random shuffle of the generated password
    $password = str_shuffle($password);

    return $password;
}


/**
 *
 * @param  int $lenght The lenght of the id to be generated
 * @return string the id returned
 * 
 * a function that generates a random id (made out only of numbers) of lenght "$lenght"
 */
function generateRandomId(int $lenght) : string{
        //generate the id
        $id = '';
        for($i=0; $i<$lenght; $i++){
            $id .= random_int(0,9);
        }
        //a last random shuffle of the generated id
        $id = str_shuffle($id);
    
        return $id;
    }

/**
 * @return string the phone number returned
 * 
 * a function that generates random phone numbers, with different international codes
 */
function generatePhoneNumber() : string {
    // our bases 
    $code = [["+33", 9], ["0",8]];
    // a random index picked 
    $randomIndex = random_int(0, count($code)-1);
    $finalNumber = $code[$randomIndex][0];
    if ($randomIndex==1){
        $finalNumber.=random_int(1,9);
    }
    for($i=0; $i<$code[$randomIndex][1]; $i++) {  
        $finalNumber .= random_int(0,9);    
    }
    return $finalNumber;
}


function is_bissex(int $y) : bool {
    if ($y%100==0){
        if ($y%400==0){
            return true;
        }
        else {return false;}
    }

    else if ($y%4==0){return true;}
    else {return false;}

}

/**
 * @param  int $n
 * @return string
 * generates a date , a date of account creation(so quite recent if n =1 )
 * a date of birth if n =0; so a rather old date 
 */
function generateDate(int $n) : string {
    if($n){
        $year = rand(2018,2022);
    }
    else{
        $year=rand(1950,2004);
    }

    $month = rand(1,12);
    if ($month==2){
        if(is_bissex($year)){
            $day=rand(1,29);
        }
        else{
            $day=rand(1,28);
        }
    }
    else if (in_array($month,[4,6,9,11])){
        $day=rand(1,30);
    }
    else {
        $day=rand(1,31);
    }
    $date = sprintf("%04d-%02d-%02d", $year,$month, $day);
    return $date;
}

/**
 * @param  array $arr the array in which the element will be randomly picked
 * @return mixed the random element
 * 
 * returns a random element inside the array given as an argument
 */
function generateRandomElement(array $arr) : mixed {
    return $arr[random_int(0, count($arr)-1)];
}

/**
 * @param  string $begining the name of the email account 
 * @return string
 * 
 * a function that returns an email with a random domain
 */
function generateEmail( string $begining) : string {
    $mails = ['@outlook.fr', '@hotmail.fr', '@gmail.com'];
    return $begining.$mails[rand(0, count($mails)-1)];
}

// a list of names
$names = array(
        "Li", "Kim", "Patel", "Nakamoto", "Nguyen",
        "Sharma", "Chen", "Khan", "Park", "Wong",
        "Zhang", "Souza", "Boulmerka", "Mandela", "Abdi",
        "Diop", "Tafik", "Slimani", "Kaulitz", "Dupont",
        "Rodriguez", "Djebar", "Sirleaf", "Widow", "Fernández",
        "Lovelace", "Okonjo-Iweala", "Peres", "Thoroddsen", "Sánchez",
        "Smith", "Müller", "Rossi", "O'Maley", "Nyong'o",
        "Massi", "Andersen", "García", "Dubois", "Minatozaki",
        "Johnson", "Kristný", "Brown", " Munch", "Truffaud",
        "Singh", "White", "Klum", "Medjkane", "Finnbogadóttir"
    );

// a list of firstname
$firstName = array(
        "Marie", "Rosa", "Malala", "Wangari", "Emmeline", "Chimamanda", "Shirin", "Audre", "Valentina",
        "Ourdia", "Djamila", "Aïcha", "Tawakkol", "Leïla", "Ada", "Eva", "Céline", "Sojourner", "Funmilayo",
        "Gerty", "Huda", "Tenzin", "Rigoberta", "Caroline", "Annie", "Junko", "Björk","Michelangelo","Lucifer", "Lilith","Jean","Massinisa",
        "Jimmy","José","Nelson","Salam","Laurent", "Artemisia", "Frida","Mary", "Berthe", "Alice","Diavolo", "Dio", "Salvador", "Ryuk","Hamlet", "Narciss", "Roquentin", "Meursault");