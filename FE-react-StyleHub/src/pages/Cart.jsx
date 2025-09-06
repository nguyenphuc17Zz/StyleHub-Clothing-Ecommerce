import { useState, useEffect } from "react";
import { Col, Container, Row } from "react-bootstrap";
import { useDispatch } from "react-redux";
import { cartApi } from "../api/cartApi";
import { URL_IMAGE } from "../api/apiUrl";
import { toast } from "react-toastify";
import { orderApi } from "../api/orderApi";
const Cart = () => {
  const [cartList, setCartList] = useState([]);
  const dispatch = useDispatch();

  // Lấy dữ liệu giỏ hàng từ API
  const fetchCart = async () => {
    try {
      const res = await cartApi.createCart();
      if (res.success) {
        setCartList(res.cart_items);
        console.log(res.cart_items);
      }
    } catch (e) {
      console.error("Lỗi lấy cart:", e);
    }
  };

  // Tính tổng giá

  let totalPrice = (cartList || []).reduce(
    (sum, item) => sum + Number(item.product.price) * item.quantity,
    0
  );

  useEffect(() => {
    window.scrollTo(0, 0);
    fetchCart();
  }, []);

  const fetchDeleteItem = async (item) => {
    try {
      const data = {
        product_id: item.product.id,
        variant_id: item.variant.id,
      };
      const res = await cartApi.deleteItem(data);
      if (res.success) {
        setCartList(res.cart_items);
        toast.success("Product has been deleted to cart!");
      }
    } catch (error) {
      toast.error(error);
    }
  };
  const fetchAddItem = async (item, t) => {
    try {
      let quantity = 0;
      if (t === "plus") {
        quantity = 1;
      } else {
        quantity = -1;
      }
      const data = {
        product_id: item.product.id,
        variant_id: item.variant.id,
        quantity: quantity,
      };
      const res = await cartApi.updateAddItemCard(data);
      if (res.success) {
        setCartList(res.cart_items);
        toast.success("Product has been added to cart!");

        console.log(cartList);
      }
    } catch (error) {
      console.error("Lỗi lấy cart:", error);
    }
  };

  // Hàm cập nhật số lượng (placeholder)
  const updateCart = async (item, t) => {
    await fetchAddItem(item, t);
    // dispatch hoặc gọi API cập nhật ở đây
  };

  // Hàm xóa sản phẩm (placeholder)
  const deleteItem = async (item) => {
    await fetchDeleteItem(item);
  };

  // THANH TOAN
  const checkOut = async () => {
    try {
      const items = cartList.map((i) => ({
        product_id: i.product.id,
        variant_id: i.variant.id,
        quantity: i.quantity,
        price: i.quantity * i.product.price,
      }));

      const data = {
        order: {
          total_amount: totalPrice,
          status: "pending",
        },
        items,
      };

      const res = await orderApi.checkOut(data);

      if (res.success) {
        toast.success("Tạo đơn hàng thành công");

        const res_2 = await cartApi.createCart();
        if (res_2.success) {
          setCartList(res_2.cart_items ?? []);
        }
      } else {
        toast.error(res.message || "Không thể tạo đơn hàng");
      }
    } catch (err) {
      console.error("Checkout error:", err);
      toast.error("Có lỗi xảy ra khi checkout");
    }
  };

  return (
    <section className="cart-items">
      <Container>
        <Row className="justify-content-center">
          <Col md={8}>
            {(cartList || []).length === 0 && (
              <h2 className="no-items">Your cart is empty</h2>
            )}

            {(cartList || []).map((item) => {
              const lineTotal = item.product.price * item.quantity;

              return (
                <div className="cart-item" key={item.variant.id}>
                  <Row className="align-items-center">
                    {/* Thumbnail */}
                    <Col sm={4} md={3}>
                      <img
                        src={`${URL_IMAGE}/uploads/products/${item.product.thumbnail}`}
                        alt={item.product.name}
                        className="img-fluid cart-thumbnail"
                      />
                    </Col>

                    {/* Thông tin sản phẩm */}
                    <Col sm={8} md={5} className="cart-details">
                      <h3>{item.productName}</h3>
                      <p>Size: {item.variant.size}</p>
                      <p>Color: {item.variant.color}</p>
                    </Col>

                    {/* Điều khiển số lượng và giá */}
                    <Col sm={12} md={4} className="cart-controls">
                      <div className="d-flex align-items-center mb-2">
                        <button
                          className="btn btn-outline-secondary me-2"
                          onClick={() => updateCart(item, "minus")}
                        >
                          –
                        </button>

                        <span className="fw-bold">{item.quantity}</span>

                        <button
                          className="btn btn-outline-secondary ms-2"
                          onClick={() => updateCart(item, "plus")}
                        >
                          +
                        </button>
                      </div>

                      <div>
                        <span className="me-3">
                          Price: ${item.product.price}
                        </span>
                        <span>Total: ${lineTotal}</span>
                      </div>

                      <button
                        className="btn btn-link text-danger mt-2"
                        onClick={() => deleteItem(item)}
                      >
                        Remove
                      </button>
                    </Col>
                  </Row>
                </div>
              );
            })}
          </Col>

          {/* Tổng kết */}
          <Col md={4}>
            <div className="cart-summary p-3 border rounded shadow-sm">
              <h2>Cart Summary</h2>
              <div className="d-flex justify-content-between my-3">
                <h4>Total Price:</h4>
                <h4 style={{ color: "#28a745", fontWeight: "bold" }}>
                  ${totalPrice.toFixed(2)}
                </h4>
              </div>

              <button
                style={{
                  width: "100%",
                  padding: "12px",
                  backgroundColor: "#007bff",
                  color: "#fff",
                  border: "none",
                  borderRadius: "8px",
                  fontSize: "18px",
                  fontWeight: "600",
                  cursor: "pointer",
                  transition: "0.3s ease",
                }}
                onMouseOver={(e) =>
                  (e.target.style.backgroundColor = "#0056b3")
                }
                onMouseOut={(e) => (e.target.style.backgroundColor = "#007bff")}
                onClick={checkOut}
              >
                Proceed to Checkout
              </button>
            </div>
          </Col>
        </Row>
      </Container>
    </section>
  );
};

export default Cart;
