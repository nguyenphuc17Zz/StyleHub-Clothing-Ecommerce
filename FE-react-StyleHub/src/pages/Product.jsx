import { Fragment, useEffect, useState } from "react";
import { useParams } from "react-router-dom";
import { Container } from "react-bootstrap";
import Banner from "../components/Banner/Banner";
import ProductDetails from "../components/ProductDetails/ProductDetails";
import ProductReviews from "../components/ProductReviews/ProductReviews";
import ShopList from "../components/ShopList";
import axios from "axios";
import useWindowScrollToTop from "../hooks/useWindowScrollToTop";
import { productApi } from "../api/productApi";

const Product = () => {
  const { id } = useParams();
  const [selectedProduct, setSelectedProduct] = useState(null);
  const [relatedProducts, setRelatedProducts] = useState([]);

  useWindowScrollToTop();

  useEffect(() => {
    const fetchProduct = async () => {
      try {
        const res = await productApi.productDetail(id);

        setSelectedProduct(res.product); 
        setRelatedProducts(res.related); 
      } catch (err) {
        console.error(err);
      }
    };

    fetchProduct();
  }, [id]);

  if (!selectedProduct) return <p>Loading...</p>;

  return (
    <Fragment>
      <Banner title={selectedProduct.name} />
      <ProductDetails selectedProduct={selectedProduct} />
      <section className="related-products">
        <Container>
          <h3>You might also like</h3>
        </Container>
        <ShopList productItems={relatedProducts} />
      </section>
    </Fragment>
  );
};

export default Product;
