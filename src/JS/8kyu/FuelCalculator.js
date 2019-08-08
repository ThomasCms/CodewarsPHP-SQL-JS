function fuelPrice(litres, pricePerLiter) {
    for (let i = 2; i <= 10; i +=2) { //discount loop
        if (litres >= i) {
            pricePerLiter -= 0.05;
        }
    }
    return Math.round(litres * pricePerLiter * 100) / 100;
}
