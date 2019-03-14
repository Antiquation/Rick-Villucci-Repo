import {WishList, WishListItem} from "./Wishlist";

it("can create an instance of a model", () =>{
    const item = WishListItem.create({
        "name": "Chronicals of Narnia",
        "price": 28.73,
        "image": "https://www.google.com/imgres?imgurl=https%3A%2F%2Fstatic.concursolutions.com%2Fstatic%2Fimages%2Fbrand%2Flogo-SAP-20171204.svg&imgrefurl=https%3A%2F%2Fwww.htbridge.com%2Fwebsec%2F%3Fid%3DboqwShhx&docid=w3xcYbRK8tHYhM&tbnid=WDbUMoHm7-bjBM%3A&vet=10ahUKEwj__q2fp-7gAhX0JDQIHaHdCBEQMwgnKAAwAA..i&w=800&h=389&itg=1&bih=1031&biw=2064&q=%2Fstatic%2Fimages%2Fbrand%2Flogo-SAP-20171204.svg&ved=0ahUKEwj__q2fp-7gAhX0JDQIHaHdCBEQMwgnKAAwAA&iact=mrc&uact=8"

    });
    
    expect(item.price).toBe(28.73);
    item.changeName("Narnia");
    item.changePrice(30.05);
    item.changeImage("SomeNewUrl");
    expect(item.name).toBe("Narnia");
    expect(item.price).toBe(30.05);
    expect(item.image).toBe("SomeNewUrl");
    
});

it("can create a list of items", () => {
    const list = WishList.create({
        items: [
            {
                "name": "Chronicals of Narnia",
                "price": 28.73,
                "image": "https://www.google.com/imgres?imgurl=https%3A%2F%2Fstatic.concursolutions.com%2Fstatic%2Fimages%2Fbrand%2Flogo-SAP-20171204.svg&imgrefurl=https%3A%2F%2Fwww.htbridge.com%2Fwebsec%2F%3Fid%3DboqwShhx&docid=w3xcYbRK8tHYhM&tbnid=WDbUMoHm7-bjBM%3A&vet=10ahUKEwj__q2fp-7gAhX0JDQIHaHdCBEQMwgnKAAwAA..i&w=800&h=389&itg=1&bih=1031&biw=2064&q=%2Fstatic%2Fimages%2Fbrand%2Flogo-SAP-20171204.svg&ved=0ahUKEwj__q2fp-7gAhX0JDQIHaHdCBEQMwgnKAAwAA&iact=mrc&uact=8"

            }
        ]
    });
    expect(list.items.length).toBe(1);
    expect(list.items[0].price).toBe(28.73);
    
});

it("can add new items", () =>{
    const list = WishList.create({
        items: [
            {
                "name": "Chronicals of Narnia",
                "price": 28.73,
                "image": "https://www.google.com/imgres?imgurl=https%3A%2F%2Fstatic.concursolutions.com%2Fstatic%2Fimages%2Fbrand%2Flogo-SAP-20171204.svg&imgrefurl=https%3A%2F%2Fwww.htbridge.com%2Fwebsec%2F%3Fid%3DboqwShhx&docid=w3xcYbRK8tHYhM&tbnid=WDbUMoHm7-bjBM%3A&vet=10ahUKEwj__q2fp-7gAhX0JDQIHaHdCBEQMwgnKAAwAA..i&w=800&h=389&itg=1&bih=1031&biw=2064&q=%2Fstatic%2Fimages%2Fbrand%2Flogo-SAP-20171204.svg&ved=0ahUKEwj__q2fp-7gAhX0JDQIHaHdCBEQMwgnKAAwAA&iact=mrc&uact=8"

            }
        ]
    });
    
    list.add(WishListItem.create({
        name: "Ichy Ricky",
        price: 10.00
    }))
    expect(list.items.length).toBe(2);
    expect(list.items[0].name).toBe("Chronicals of Narnia");
    list.items[0].changeImage("happy");
    expect(list.items[0].image).toBe("happy");
    
})

