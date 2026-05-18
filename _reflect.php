<?php
require __DIR__ . '/vendor/autoload.php';

function getPublicApi(\, \ = '') {
    try {
        \ = new ReflectionClass(\);
    } catch (Exception \) {
        return \ . '// Cannot reflect: ' . \ . PHP_EOL;
    }

    \ = '';
    \ .= \ . '=== ' . \ . ' ===' . PHP_EOL;
    \ = str_replace(__DIR__ . '/', '', \->getFileName());
    \ .= \ . 'File: ' . \ . PHP_EOL;
    \ .= \ . 'Parent: ' . (\->getParentClass() ? \->getParentClass()->getName() : 'none') . PHP_EOL;

    // Traits
    \ = \->getTraits();
    if (count(\) > 0) {
        \ .= \ . 'Traits:' . PHP_EOL;
        foreach (\ as \ => \) {
            \ = str_replace(__DIR__ . '/', '', \->getFileName());
            \ .= \ . '  - ' . \ . ' (file: ' . \ . ')' . PHP_EOL;
        }
    }

    // Public properties
    \ = \->getProperties(ReflectionProperty::IS_PUBLIC);
    if (count(\) > 0) {
        \ .= \ . 'Public Properties:' . PHP_EOL;
        foreach (\ as \) {
            \ = \->getDeclaringClass()->getName();
            \ = \->hasType() ? \->getType()->getName() : 'mixed';
            \ = \->getDefaultProperties();
            \ = array_key_exists(\->getName(), \) ? var_export(\[\->getName()], true) : 'undefined';
            \ .= \ . sprintf("  %s \$%s (declared in: %s, default: %s)\n", \, \->getName(), \, \);
        }
    } else {
        \ .= \ . 'Public Properties: (none)' . PHP_EOL;
    }

    // Public methods
    \ = \->getMethods(ReflectionMethod::IS_PUBLIC);
    if (count(\) > 0) {
        \ .= \ . 'Public Methods:' . PHP_EOL;
        foreach (\ as \) {
            if (\->isConstructor() || \->isDestructor()) {
                continue;
            }
            \ = \->getDeclaringClass()->getName();
            \ = [];
            foreach (\->getParameters() as \) {
                \ = '';
                if (\->hasType()) {
                    \ = \->getType();
                    if (\ instanceof ReflectionNamedType) {
                        \ .= \->getName() . ' ';
                    } elseif (\ instanceof ReflectionUnionType) {
                        \ = [];
                        foreach (\->getTypes() as \) {
                            \[] = \->getName();
                        }
                        \ .= implode('|', \) . ' ';
                    }
                }
                if (\->isVariadic()) {
                    \ .= '...';
                }
                \ .= '\$' . \->getName();
                if (\->isDefaultValueAvailable()) {
                    \ .= ' = ' . var_export(\->getDefaultValue(), true);
                }
                if (\->isPassedByReference()) {
                    \ = '&' . \;
                }
                \[] = \;
            }
            try {
                \ = '';
                if (\->hasReturnType()) {
                    \ = \->getReturnType();
                    if (\ instanceof ReflectionNamedType) {
                        \ = ': ' . \->getName();
                    } elseif (\ instanceof ReflectionUnionType) {
                        \ = [];
                        foreach (\->getTypes() as \) {
                            \[] = \->getName();
                        }
                        \ = ': ' . implode('|', \);
                    }
                }
            } catch (Exception \) {
                \ = '';
            }
            \ = Reflection::getModifierNames(\->getModifiers());
            \ = implode(' ', array_diff(\, ['public']));
            if (\ === '') { \ = 'public'; }
            \ .= \ . sprintf("  %s %s(%s)%s (declared in: %s)\n",
                \,
                \->getName(),
                implode(', ', \),
                \,
                \
            );
        }
    } else {
        \ .= \ . 'Public Methods: (none)' . PHP_EOL;
    }

    // Constants
    \ = \->getConstants();
    if (count(\) > 0) {
        \ .= \ . 'Constants:' . PHP_EOL;
        foreach (\ as \ => \) {
            \ .= \ . sprintf("  %s = %s\n", \, var_export(\, true));
        }
    }

    return \;
}

// Build full chain from User class
\ = [];
\ = new ReflectionClass('Illuminate\\Foundation\\Auth\\User');
while (\) {
    \[] = \->getName();
    \ = \->getParentClass();
}
\ = array_reverse(\);

foreach (\ as \) {
    echo getPublicApi(\);
    echo PHP_EOL;
}

// Also show the trait APIs separately
echo '=== TRAITS USED BY Illuminate\\Foundation\\Auth\\User ===' . PHP_EOL;
\ = class_uses_recursive('Illuminate\\Foundation\\Auth\\User');
foreach (\ as \) {
    \ = explode('\\\\', \);
    \ = end(\);
    // Skip concerns already shown through Model
    if (strpos(\, 'Illuminate\\Database\\Eloquent\\Concerns') === 0) {
        continue;
    }
    echo PHP_EOL . '--- Trait: ' . \ . ' ---' . PHP_EOL;
    echo getPublicApi(\);
}
