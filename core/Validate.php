<?php

namespace Core;

class Validate
{
    
    private $passed = false,
        $errors = array();

    public function check($source, array $items)
    {
        foreach ($items as $item => $rules) {
            foreach ($rules as $rule => $rule_value) {

                $value = sanitize(trim($source->$item));

                $rule_value = sanitize($rule_value);

                if ($rule === 'required' && empty($value)) {
                    $this->addError("{$rules['name']} is required");
                } elseif (!empty($value)) {
                    switch ($rule) {
                        case 'integer':
                            if (!is_numeric($value)) {
                                $this->addError("{$rules['name']} must be a number.");
                            }
                            break;
                        case 'string':
                            if (!is_string($value)) {
                                $this->addError("{$rules['name']} must be a string.");
                            }
                            break;
                    }
                }
            }
        }

        if (empty($this->errors)) {
            $this->passed = true;
        }

        return $this;
    }

    private function addError($error)
    {
        $this->errors[] = $error;
    }

    public function errors()
    {
        return $this->errors;
    }

    public function passed()
    {
        return $this->passed;
    }
}