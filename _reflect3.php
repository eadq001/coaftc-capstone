<?php
require __DIR__ . "/vendor/autoload.php";

function getPublicApi($className, $indent = "") {
    try {
        $ref = new ReflectionClass($className);
    } catch (Exception $e) {
        return $indent . "// Cannot reflect: " . $className . PHP_EOL;
    }

    $output = "";
    $output .= $indent . "=== " . $className . " ===" . PHP_EOL;
    $file = str_replace(__DIR__ . "/", "", $ref->getFileName());
    $output .= $indent . "File: " . $file . PHP_EOL;
    $output .= $indent . "Parent: " . ($ref->getParentClass() ? $ref->getParentClass()->getName() : "none") . PHP_EOL;

    $traits = $ref->getTraits();
    if (count($traits) > 0) {
        $output .= $indent . "Traits:" . PHP_EOL;
        foreach ($traits as $traitName => $traitRef) {
            $tfile = str_replace(__DIR__ . "/", "", $traitRef->getFileName());
            $output .= $indent . "  - " . $traitName . " (file: " . $tfile . ")" . PHP_EOL;
        }
    }

    $props = $ref->getProperties(ReflectionProperty::IS_PUBLIC);
    if (count($props) > 0) {
        $output .= $indent . "Public Properties:" . PHP_EOL;
        foreach ($props as $prop) {
            $declaringClass = $prop->getDeclaringClass()->getName();
            $type = $prop->hasType() ? $prop->getType()->getName() : "mixed";
            $defaults = $ref->getDefaultProperties();
            $default = array_key_exists($prop->getName(), $defaults) ? var_export($defaults[$prop->getName()], true) : "undefined";
            $output .= $indent . sprintf("  %s \$%s (declared in: %s, default: %s)\n", $type, $prop->getName(), $declaringClass, $default);
        }
    } else {
        $output .= $indent . "Public Properties: (none)" . PHP_EOL;
    }

    $methods = $ref->getMethods(ReflectionMethod::IS_PUBLIC);
    if (count($methods) > 0) {
        $output .= $indent . "Public Methods:" . PHP_EOL;
        foreach ($methods as $method) {
            if ($method->isConstructor() || $method->isDestructor()) {
                continue;
            }
            $declaringClass = $method->getDeclaringClass()->getName();
            $params = [];
            foreach ($method->getParameters() as $param) {
                $paramStr = "";
                if ($param->hasType()) {
                    $paramType = $param->getType();
                    if ($paramType instanceof ReflectionNamedType) {
                        $paramStr .= $paramType->getName() . " ";
                    } elseif ($paramType instanceof ReflectionUnionType) {
                        $names = [];
                        foreach ($paramType->getTypes() as $t) {
                            $names[] = $t->getName();
                        }
                        $paramStr .= implode("|", $names) . " ";
                    }
                }
                if ($param->isVariadic()) {
                    $paramStr .= "...";
                }
                $paramStr .= "\$" . $param->getName();
                if ($param->isDefaultValueAvailable()) {
                    $paramStr .= " = " . var_export($param->getDefaultValue(), true);
                }
                if ($param->isPassedByReference()) {
                    $paramStr = "&" . $paramStr;
                }
                $params[] = $paramStr;
            }
            $returnTypeStr = "";
            if ($method->hasReturnType()) {
                $rt = $method->getReturnType();
                if ($rt instanceof ReflectionNamedType) {
                    $returnTypeStr = ": " . $rt->getName();
                }
            }
            $modifiers = Reflection::getModifierNames($method->getModifiers());
            $modStr = implode(" ", array_diff($modifiers, ["public"]));
            if ($modStr === "") { $modStr = "public"; }
            $output .= $indent . sprintf("  %s %s(%s)%s (declared in: %s)\n",
                $modStr,
                $method->getName(),
                implode(", ", $params),
                $returnTypeStr,
                $declaringClass
            );
        }
    } else {
        $output .= $indent . "Public Methods: (none)" . PHP_EOL;
    }

    $constants = $ref->getConstants();
    if (count($constants) > 0) {
        $output .= $indent . "Constants:" . PHP_EOL;
        foreach ($constants as $name => $value) {
            $output .= $indent . sprintf("  %s = %s\n", $name, var_export($value, true));
        }
    }

    return $output;
}

$classes = [];
$class = new ReflectionClass("Illuminate\\Foundation\\Auth\\User");
while ($class) {
    $classes[] = $class->getName();
    $class = $class->getParentClass();
}
$classes = array_reverse($classes);

foreach ($classes as $className) {
    echo getPublicApi($className);
    echo PHP_EOL;
}

echo "=== TRAITS USED BY Illuminate\\Foundation\\Auth\\User ===" . PHP_EOL;
$traits = class_uses_recursive("Illuminate\\Foundation\\Auth\\User");
foreach ($traits as $traitName) {
    if (strpos($traitName, "Illuminate\\Database\\Eloquent\\Concerns") === 0) {
        continue;
    }
    echo PHP_EOL . "--- Trait: " . $traitName . " ---" . PHP_EOL;
    echo getPublicApi($traitName);
}
