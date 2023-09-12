<?php

namespace Utils;

use Exception;

class Validation
{
  public static function validateEmail($email)
  {
    if (preg_match('/^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/', $email)) {
      return $email;
    }
    throw new Exception('Invalid email');
  }
  
  // public static function validateName($name)
  // {
  //   if (preg_match('/^[a-zA-Z]+(([\',. -][a-zA-Z ])?[a-zA-Z]*)*$/', $name)) {
  //     return $name;
  //   }
  //   throw new Exception('Invalid name');
  // }

  public static function validateName($name)
  {
    if (preg_match('/^[a-zA-ZÀ-ỹ\s\'-]*$/', $name)) {
        return $name;
    }
    throw new Exception('Invalid name');
  }


  public static function validatePhone($phone)
  {
    if (preg_match('/^[0-9]{10}$/', $phone)) {
      return $phone;
    }
    throw new Exception('Invalid phone number');
  }

  public static function validatePassword($password)
  {
    if (preg_match('/^[a-zA-Z0-9!@#$%^&*()_+\-=\[\]{};\'\\:"|<,\.>\/?]{8,30}$/', $password)) {
      return $password;
    }
    throw new Exception('Invalid password');
  }
  public static function validateCreditCardNumber($cardNumber)
  {
    if (preg_match('/^4[0-9]{12}(?:[0-9]{3})?$/', $cardNumber)) {
      return $cardNumber;
    }
    throw new Exception('Invalid credit card number');
  }

  public static function validateCreditCardExpiry($expiry)
  {
    if (preg_match('/^(0[1-9]|1[0-2])\/?([0-9]{4}|[0-9]{2})$/', $expiry)) {
      return $expiry;
    }
    throw new Exception('Invalid credit card expiry');
  }

  public static function validateCreditCardCvv($cvv)
  {
    if (preg_match('/^[0-9]{3,4}$/', $cvv)) {
      return $cvv;
    }
    throw new Exception('Invalid credit card cvv');
  }

  public static function validateCreditCardName($name)
  {
    if (preg_match('/^[a-zA-Z ]{2,30}$/', $name)) {
      return $name;
    }
    throw new Exception('Invalid credit card name');
  }

  public static function isInteger($number)
  {
    if (preg_match('/^[0-9]+$/', $number)) {
      return $number;
    }
    throw new Exception('Invalid number');
  }
}