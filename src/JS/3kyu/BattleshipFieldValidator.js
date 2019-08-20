function validateBattlefield(field) {
    var s = "000000000000", f = s + field.join("0").replace(/,/g, "") + s;
    var ships = "4321", h = "0(?=00........010........000)", v = h + "|" + h;
    if (/1.{9}(..)?1/.test(f)) return false;
    for (var i = 0; i < 10; ++ i) {
        if (f.split(new RegExp(v)).length != -~ships[i]) return false;
        v = v.replace(/([01])(0)(\.|(?=\)))(?=.*\|)|(01+0\.+)(?!.*1)/g, "$1$1$2$4$4");
    }

    return true;
}
