import { useState } from "react";
import { Col, Container, Row } from "react-bootstrap";
import { useDispatch } from "react-redux";
import { toast } from "react-toastify";
import { addToCart } from "../../app/features/cart/cartSlice";
import "./product-details.css";
import { cartApi } from "../../api/cartApi";
import { productApi } from "../../api/productApi";

const ProductDetails = ({ selectedProduct }) => {
  const [quantity, setQuantity] = useState(1);
  const [selectedSize, setSelectedSize] = useState(null);
  const [selectedColor, setSelectedColor] = useState(null);
  const [mainImg, setMainImg] = useState(
    selectedProduct?.thumbnail
      ? `http://localhost:8000/uploads/products/${selectedProduct.thumbnail}`
      : ""
  );

  // 👉 state cho gợi ý size
  const [showSuggest, setShowSuggest] = useState(false);
  const [weight, setWeight] = useState("");
  const [age, setAge] = useState("");
  const [height, setHeight] = useState("");
  const [suggestedSize, setSuggestedSize] = useState(null);

  const getStock = () => {
    if (!selectedSize || !selectedColor) return 0;
    const variant = selectedProduct.variants[selectedSize].find(
      (v) => v.color === selectedColor
    );
    return variant ? variant.stock : 0;
  };

  const handleQuantityChange = (e) => {
    const max = getStock();
    let val = Number(e.target.value);
    if (val > max) val = max;
    if (val < 1) val = 1;
    setQuantity(val);
  };

  const handleAdd = async () => {
    const stock = getStock();
    if (!selectedSize || !selectedColor) {
      toast.error("Please select size and color!");
      return;
    }
    if (quantity > stock) {
      toast.error(`Only ${stock} items in stock`);
      return;
    }
    await fetchAddItem();
  };

  // CART ITEM
  const fetchAddItem = async () => {
    try {
      const variant = selectedProduct.variants[selectedSize].find(
        (v) => v.color === selectedColor
      );
      const variant_id = variant ? variant.id : null;
      const data = {
        product_id: selectedProduct.id,
        variant_id: variant_id,
        quantity: quantity,
      };
      const res = await cartApi.updateAddItemCard(data);
      if (res.success) {
        toast.success("Product has been added to cart!");
      }
    } catch (error) {
      console.error("Lỗi lấy categories:", error);
    }
  };

  //  API gợi ý size
  const fetchSuggestSize = async () => {
    if (!weight.trim() || !age.trim() || !height.trim()) {
      toast.error("Vui lòng nhập đủ cân nặng, tuổi và chiều cao!");
      return;
    }
    try {
      const res = await productApi.sizeSuggestion(weight, age, height);
      if (res.success) {
        console.log(res);
        setSuggestedSize(res.size);
      }
    } catch (error) {
      console.error("Error predicting size:", error);
      toast.error("Lỗi gọi API gợi ý size");
    }
  };

  return (
    <section className="product-page">
      <Container>
        <Row className="justify-content-center">
          {/* Left: Main Image + Gallery */}
          <Col md={6}>
            <img
              loading="lazy"
              src={mainImg}
              alt={selectedProduct?.name}
              className="main-img"
            />
            <div className="gallery">
              <img
                src={`http://localhost:8000/uploads/products/${selectedProduct.thumbnail}`}
                alt="main"
                className="gallery-img"
                onMouseEnter={() =>
                  setMainImg(
                    `http://localhost:8000/uploads/products/${selectedProduct.thumbnail}`
                  )
                }
              />
              {selectedProduct?.images?.map((img) => (
                <img
                  key={img.id}
                  src={`http://localhost:8000/uploads/images/${img.url}`}
                  alt={selectedProduct?.name}
                  className="gallery-img"
                  onMouseEnter={() =>
                    setMainImg(
                      `http://localhost:8000/uploads/images/${img.url}`
                    )
                  }
                />
              ))}
            </div>
          </Col>

          {/* Right: Info */}
          <Col md={6}>
            <h2>{selectedProduct?.name}</h2>
            <div className="info">
              <span className="price">${selectedProduct?.price}</span>
              <span>Category: {selectedProduct?.category?.name}</span>
            </div>

            {/* Sizes */}
            <div className="variants">
              <strong>Select Size:</strong>
              <div className="sizes">
                {selectedProduct?.variants &&
                  Object.keys(selectedProduct.variants).map((size) => (
                    <button
                      key={size}
                      className={`size-btn ${
                        selectedSize === size ? "active" : ""
                      }`}
                      onClick={() => {
                        setSelectedSize(size);
                        setSelectedColor(null);
                        setQuantity(1);
                      }}
                    >
                      {size}
                    </button>
                  ))}
              </div>

              {/* Colors */}
              {selectedSize && (
                <>
                  <strong>Select Color:</strong>
                  <div className="colors">
                    {selectedProduct.variants[selectedSize].map((v) => (
                      <button
                        key={v.id}
                        className={`color-btn ${
                          selectedColor === v.color ? "active" : ""
                        }`}
                        onClick={() => {
                          setSelectedColor(v.color);
                          setQuantity(1);
                        }}
                        disabled={v.stock === 0}
                        title={v.stock === 0 ? "Out of stock" : ""}
                      >
                        {v.color} {v.stock === 0 ? "(0)" : ""}
                      </button>
                    ))}
                  </div>
                </>
              )}
            </div>

            {/* Quantity + Add to Cart */}
            <input
              className="qty-input"
              type="number"
              min={1}
              max={getStock()}
              value={quantity}
              onChange={handleQuantityChange}
            />
            <button type="button" className="add" onClick={handleAdd}>
              Add To Cart
            </button>

            {/* 👉 Gợi ý size */}
            <div className="size-suggest">
              <button
                type="button"
                className="toggle-suggest"
                onClick={() => setShowSuggest(!showSuggest)}
              >
                {showSuggest
                  ? "Hide Size Recommendation"
                  : "Show Size Recommendation"}
              </button>

              {showSuggest && (
                <div className="suggest-form">
                  <input
                    type="number"
                    placeholder="Weight (kg)"
                    value={weight}
                    onChange={(e) => setWeight(e.target.value)}
                  />
                  <input
                    type="number"
                    placeholder="Age"
                    value={age}
                    onChange={(e) => setAge(e.target.value)}
                  />
                  <input
                    type="number"
                    placeholder="Height (cm)"
                    value={height}
                    onChange={(e) => setHeight(e.target.value)}
                  />
                  <button type="button" onClick={fetchSuggestSize}>
                    Suggest Size
                  </button>

                  {suggestedSize && (
                    <p className="suggest-result">
                      👉 Recommended Size: <strong>{suggestedSize}</strong>
                    </p>
                  )}
                </div>
              )}
            </div>
          </Col>
        </Row>
      </Container>
    </section>
  );
};

export default ProductDetails;
