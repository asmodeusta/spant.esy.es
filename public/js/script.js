var dom=function(v,i,n,c,e){
    n=function(a,b){
        return new c(a,b);
    };
    c=function(a,b){
        i.push.apply(this,
            a?
                a.nodeType||a===window?
                    [a]
                    :""+a===a?
                    /</.test(a)?
                        ((e=v.createElement(b||"q")).innerHtml=a,e.children)
                        :(b&&n(b)[0]||v).querySelectorAll(a)
                    :/f/.test(typeof a)?
                        /c/.test(v.readyState)?
                            a()
                            :v.addEventListener("DOMContentLoaded",a)
                        :a
                :i);
    };
    n.fn=c.prototype=i;
    n.one=function(a,b){return n(a,b)[0]||null;};
    return n;
}(document,[]);

var f1;
var f2;
var timeouts = [];

var ajax = function(o){
    var a={
        m:'method'in o?o.method:'POST',
        u:'url'in o?o.url:window.location.pathname,
        p:'params'in o?o.params:{},
        h:'headers'in o?o.headers:{},
        e:'element'in o?o.element:this,
        bs:'beforeSend'in o?o.beforeSend:function(e){},
        ol:'onload'in o?o.onload:function(r){},
        os:'onsuccess'in o?o.onsuccess:function(r){},
        oe:'onerror'in o?o.onerror:function(r){},
        od:'ondone'in o?o.ondone:function(r){},
        send:function(){
            r=new XMLHttpRequest();
            r.onreadystatechange=function(){
                if(r.readyState===4){
                    var rt=r.responseText;
                    a.ol(rt);
                    if(r.status === 200){
                        a.os(rt);
                    }else{
                        a.oe(rt);
                    }
                    a.od(rt);
                }
            };
            r.open(a.m,a.u);
            r.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
            r.setRequestHeader("X-Requested-With","XMLHttpRequest");
            for(var n in a.h){r.setRequestHeader(n,a.h[n]);};
            var p='ajaxCall=1';for(var n in a.p){p+='&'+n+'='+a.p[n];}
            a.bs(a.e);
            r.send(p);
            return a;
        }
    };
    return a.send();
};

function timeout(func, duration) {
    var t = setTimeout(function() {
        setTimeout(func(), duration);
    });
    timeouts.push(t);
}

function clear_timeout() {
    timeouts.forEach(function (element) {
        clearTimeout(element);
    })
}

function fighter(o, id) {
    if (!((typeof(o)==='object')&&(o!==null))) {
        o = {};
    }
    var f = {
        avatar: 'avatar' in o?o.avatar:{
            x:1,
            y:1,
        },
        strength: 'strength' in o?o.strength:0,
        agility: 'agility' in o?o.agility:0,
        stamina: 'stamina' in o?o.stamina:0,
        max_hp: 'hp' in o?o.hp:30,
        hp: 'hp' in o?o.hp:30,
        element: null,
        hit: function(hp) {
            var hp = Math.max(0,hp);
            this.hp = hp;
            var hp_ind = hp/this.max_hp*100;
            if (this.element!==null) {
                var e_hp = dom.one('.hp', e);

                var e_hp_text = dom.one('.text', e_hp);
                e_hp_text.innerHTML = 'HP: '+this.hp+'/'+this.max_hp;

                var e_hp_ind = dom.one('.ind', e_hp);
                e_hp_ind.style = 'width: '+parseInt(hp_ind)+'%;';
            }
        },
        log_start: function() {
            log('round', 'Fighter '+id[1]+': Strength = '+this.strength+', Agility = '+this.agility+', Stamina = '+this.stamina+
                ', HP = '+this.hp);
        },
        log_end: function() {
            log('round', 'Fighter '+id[1]+': HP = '+this.hp);
        }
    };
    var e = dom.one('#'+id);
    if(e!=null) {
        f.element = e;

        var e_hp = dom.one('.hp', e);

        var e_hp_text = dom.one('.text', e_hp);
        e_hp_text.innerHTML = 'HP: '+f.hp+'/'+f.hp;

        var e_hp_ind = dom.one('.ind', e_hp);
        e_hp_ind.style = 'width: 100%;';

        var avatar_class = "x"+f.avatar.x+" y"+f.avatar.y;
        var e_avatar_div = dom.one('.avatar > div', e);
        e_avatar_div.attributes.class.value = avatar_class;

        var e_strength = dom.one('.strength', e);
        var e_agility = dom.one('.agility', e);
        var e_stamina = dom.one('.stamina', e);
        e_strength.innerHTML = 'Strength: '+f.strength;
        e_agility.innerHTML = 'Agility: '+f.agility;
        e_stamina.innerHTML = 'Stamina: '+f.stamina;
    }
    return f;
}

function hide(e, duration) {
    if(typeof(e)==="object") {
        setTimeout(function() {
            e.classList.toggle("hide");
        }, duration);
    }
}

function damage(e, damage, duration) {
    if(typeof(e)==="object") {
        setTimeout(function() {
            e.innerHTML = damage;
            e.classList.toggle("damage");
            hide(e, duration);
        }, duration);
    }
}

function fire(e, dmg, duration) {
    if(typeof(e)==="object") {
        setTimeout(function() {
            e.classList.toggle("fire");
            damage(e, dmg, duration);
        }, 10);
    }
}

function log(c, t) {
    var ft = dom.one('.footer');
    var line = document.createElement('div');
    line.classList.add(c);
    line.innerHTML = t;
    ft.appendChild(line);
    ft.scrollTop = 9999;
}

function play_round(n, rounds) {
    var duration = 1000;

    var round = rounds[n];
    if(typeof(round)==='undefined') {
        var res = 'DRAW!';
        if(f1.hp>f2.hp) {
            res = 'FIGHTER 1 WIN!';
        } else if(f1.hp<f2.hp) {
            res = 'FIGHTER 2 WIN!';
        }
        dom.one('#round').innerHTML = res;

        log('round', "END: "+res);

        f1.log_end();
        f2.log_end();

        hide(dom.one('#endgame'),0);
        return 0;
    }
    n = round.n;

    dom.one('#round').innerHTML = "ROUND "+n;

    var a_f1 = dom.one('.attack.f1');
    var a_f2 = dom.one('.attack.f2');
    var b_f1 = document.createElement('div');
    var b_f2 = document.createElement('div');
    a_f1.appendChild(b_f1);
    a_f2.appendChild(b_f2);
    b_f1.classList.add('bullet');
    b_f2.classList.add('bullet');
    b_f1.innerHTML = round.f1.ap;
    b_f2.innerHTML = round.f2.ap;
    fire(b_f1, round.f2.dp, duration);
    fire(b_f2, round.f1.dp, duration);
    setTimeout(function () {
        f1.hit(round.f1.hp);
    }, duration);
    setTimeout(function () {
        f2.hit(round.f2.hp);
    }, duration);
    setTimeout(function() {
        log('round', "ROUND "+n+": Fighter 1 ("+round.f1.dp+"/"+round.f2.ap+"), Fighter 2 ("+round.f2.dp+"/"+round.f1.ap+")");

        play_round(n, rounds);
    }, duration);
}