import { Col } from "react-bootstrap";
import "./product-card.css";
import { useNavigate } from "react-router-dom";
import { toast } from "react-toastify";
import { useDispatch } from "react-redux";
import { addToCart } from "../../app/features/cart/cartSlice";
import { URL_IMAGE } from "../../api/apiUrl";
import { cartApi } from "../../api/cartApi";
const ProductCard = ({ productItem }) => {
  const navigate = useNavigate();

  const handleClick = () => {
    navigate(`/product/${productItem.id}`);
  };

  const handleAdd = () => {
    navigate(`/product/${productItem.id}`);
  };

  return (
    <Col md={3} sm={6} xs={12} className="product mtop">
      <img
        loading="lazy"
        onClick={handleClick}
        src={`${URL_IMAGE}/uploads/products/${productItem.thumbnail}`}
        alt={productItem.name}
        className="product-img"
      />
      <div className="product-details">
        <h3 onClick={handleClick}>{productItem.name}</h3>
        <div className="price">
          <h4>${productItem.price}</h4>
          <button
            type="button"
            className="add"
            onClick={handleAdd}
            aria-label="Add"
          >
            <ion-icon name="add"></ion-icon>
          </button>
        </div>
      </div>
    </Col>
  );
};

export default ProductCard;
