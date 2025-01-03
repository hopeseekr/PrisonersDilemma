# Prisoner's Dilemma Project

[![TravisCI](https://travis-ci.org/phpexpertsinc/skeleton.svg?branch=master)](https://travis-ci.org/phpexpertsinc/skeleton)
[![Maintainability](https://api.codeclimate.com/v1/badges/322a4c382e5ebf1a5d06/maintainability)](https://codeclimate.com/github/hopeseekr/PrisonersDilemma/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/503cba0c53eb262c947a/test_coverage)](https://codeclimate.com/github/phpexpertsinc/SimpleDTO/test_coverage)

The Prisoner's Dilemma Project is meant to see if evolutionary pressures alone are enough to arrive 
at an optimal solution to this age old philosophical thought experiment.

It uses the [**PHP Evolver genetic algorithm framework**](https://github.com/PHPExpertsInc/php-evolver).

## Installation

Via Composer

```bash
composer create-project hopeseekr/prisoners-dilemma
```

## Usage

```bash
# Play the classic Prisoner's Dilemma yourself against a completely irrational opponent.
./dilemma play

# Run the Genetic Algorithm to see how Nature would evolve a strategy, assuming everyone is *purely* rational.
./dilemma evolve:classic
```

![image](https://user-images.githubusercontent.com/1125541/86166578-9f82ae80-bada-11ea-8ee6-ef70d5fc8f1f.png)



## Testing

*No tests have been created. I'm very open to contributions.*

```bash
phpunit --testdox
```

# Contributors

[Theodore R. Smith](https://www.phpexperts.pro/]) <theodore@phpexperts.pro>  
GPG Fingerprint: 4BF8 2613 1C34 87AC D28F  2AD8 EB24 A91D D612 5690  
CEO: PHP Experts, Inc.

## License

MIT license. Please see the [license file](LICENSE) for more information.

