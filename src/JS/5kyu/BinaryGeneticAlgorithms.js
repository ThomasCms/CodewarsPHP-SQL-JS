let GeneticAlgorithm = function () {};

GeneticAlgorithm.prototype.run = function(fitness, length, p_c, p_m, iterations = 150) {
    const sizeOfPopulation = 100;
    const myAssess = assess(fitness);
    const myCrossover = crossover(p_c, length);
    const myMutate = mutate(p_m);

    let thisGeneration = createFirstGeneration(sizeOfPopulation, length)
        .map(myAssess)
        .sort(byDescendingFitness);

    while (iterations-- > 0 && thisGeneration[0].fitness !== 1) {
        let nextGeneration = [];
        while (nextGeneration.length < thisGeneration.length) {
            const mom = select(thisGeneration);
            const dad = select(thisGeneration);
            const kids = myCrossover(mom.chromosome, dad.chromosome)
                .map(myMutate)
                .map(myAssess);
            nextGeneration.push(...kids);
        }
        thisGeneration = nextGeneration.sort(byDescendingFitness);
    }

    return thisGeneration[0].chromosome;
};

const createFirstGeneration = (sizeOfPopulation, lengthOfChromosome) =>
    Array(sizeOfPopulation)
        .fill()
        .map(() => generate(lengthOfChromosome));

const byDescendingFitness = (a,b) =>
    b.fitness - a.fitness;

const generate = lengthOfChromosome =>
    Array(lengthOfChromosome)
        .fill()
        .map(() => Math.floor(Math.random() + 0.5))
        .join("");

const assess = fitness => chromosome => ({
    chromosome: chromosome,
    fitness: fitness(chromosome)
});

const select = population => {
    const wheel = population.map(e => e.fitness).reduce((a,b) => a + b);
    let spin = Math.random() * wheel;
    return population.find(e => (spin-= e.fitness) <= 0);
};

const crossover = (probability, lengthOfChromosome) => (chromosome1, chromosome2) => {
    if (Math.random() >= probability)
        return [ chromosome1, chromosome2 ];
    const at = Math.floor(Math.random() * lengthOfChromosome);
    return [
        chromosome1.substring(0, at) + chromosome2.substring(at),
        chromosome2.substring(0, at) + chromosome1.substring(at)
    ];
};

const mutate = probability => chromosome =>
    chromosome
        .split("")
        .map(bit => Math.random() < probability ? [1,0][bit] : bit)
        .join("");
