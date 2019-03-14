import {types} from "mobx-state-tree";

const data = {
  "name": "Chronicals of Narnia",
  "price": 28.73,
  "image": "https://www.google.com/imgres?imgurl=https%3A%2F%2Fstatic.concursolutions.com%2Fstatic%2Fimages%2Fbrand%2Flogo-SAP-20171204.svg&imgrefurl=https%3A%2F%2Fwww.htbridge.com%2Fwebsec%2F%3Fid%3DboqwShhx&docid=w3xcYbRK8tHYhM&tbnid=WDbUMoHm7-bjBM%3A&vet=10ahUKEwj__q2fp-7gAhX0JDQIHaHdCBEQMwgnKAAwAA..i&w=800&h=389&itg=1&bih=1031&biw=2064&q=%2Fstatic%2Fimages%2Fbrand%2Flogo-SAP-20171204.svg&ved=0ahUKEwj__q2fp-7gAhX0JDQIHaHdCBEQMwgnKAAwAA&iact=mrc&uact=8"
};

export const WishListItem = types.model({
  name: types.string,
  price: types.number,
  image: ""
}).actions(self => ({
    
  changeName(newName: string) {
      self.name = newName
  },
  changePrice (price: number){
    self.price = price
  },
  changeImage (newImage: string){
    self.image = newImage
  }
  
}));

export const WishList = types.model({
  items: types.optional(types.array(WishListItem),[])
}).
actions(self => ({
  add(item: any){
    self.items.push(item);
  }
}));