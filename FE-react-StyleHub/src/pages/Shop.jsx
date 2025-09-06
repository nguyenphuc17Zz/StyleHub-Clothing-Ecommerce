import { Container, Row, Col, Button, Pagination } from "react-bootstrap";
import FilterSelect from "../components/FilterSelect";
import SearchBar from "../components/SeachBar/SearchBar";
import { Fragment, useState, useEffect } from "react";
import ShopList from "../components/ShopList";
import Banner from "../components/Banner/Banner";
import useWindowScrollToTop from "../hooks/useWindowScrollToTop";
import { categoryApi } from "../api/categoryApi";
import { productApi } from "../api/productApi";
import { useSearchParams } from "react-router-dom";

const Shop = () => {
  const [categories, setCategories] = useState([]);
  const [productsList, setProductList] = useState([]);
  const [totalPages, setTotalPages] = useState(1);
  const [loading, setLoading] = useState(true);

  const [searchParams, setSearchParams] = useSearchParams();

  // Params hiện tại
  const category = searchParams.get("category_id") || "all";
  const search = searchParams.get("search") || "";
  const page = Number(searchParams.get("page")) || 1;

  // Params tạm (thay đổi khi chọn lọc nhưng chưa Apply)
  const [tempCategory, setTempCategory] = useState(category);
  const [tempSearch, setTempSearch] = useState(search);

  // Lấy categories
  useEffect(() => {
    const fetchCategories = async () => {
      try {
        const res = await categoryApi.getAllCategory();
        if (res.success) {
          setCategories(res.categories);
        }
      } catch (e) {
        console.error("Lỗi lấy categories:", e);
      }
    };
    fetchCategories();
  }, []);

  // Lấy products khi params thay đổi
  useEffect(() => {
    const fetchProducts = async () => {
      setLoading(true);
      try {
        const params = { category_id: category, search, page };
        const res = await productApi.getAllProduct(params);
        if (res.success) {
          setProductList(res.products.data);
          setTotalPages(res.products.last_page);
        }
      } catch (e) {
        console.error("Lỗi lấy products:", e);
      } finally {
        setLoading(false);
      }
    };
    fetchProducts();
  }, [category, search, page]);

  // Chạy mặc định khi vào /shop lần đầu
  useEffect(() => {
    if (
      !searchParams.get("page") &&
      !searchParams.get("category_id") &&
      !searchParams.get("search")
    ) {
      setSearchParams({ category_id: "all", search: "", page: 1 });
    }
  }, [searchParams, setSearchParams]);

  useWindowScrollToTop();

  // Cập nhật params tạm
  const handleFilterChange = (selectedOption) =>
    setTempCategory(selectedOption.value);
  const handleSearchChange = (value) => setTempSearch(value);

  // Áp dụng filter khi bấm nút
  const handleApplyFilter = () => {
    setSearchParams({ category_id: tempCategory, search: tempSearch, page: 1 });
  };

  // Chuyển trang
  const handlePageChange = (p) => {
    setSearchParams({ category_id: category, search, page: p });
  };

  return (
    <Fragment>
      <Banner title="product" />
      <section className="filter-bar">
        <Container className="filter-bar-contianer">
          <Row className="justify-content-center align-items-center g-2">
            <Col md={3}>
              <FilterSelect
                categories={categories}
                onFilterChange={handleFilterChange}
              />
            </Col>
            <Col md={5}>
              <SearchBar onSearchChange={handleSearchChange} />
            </Col>
            <Col md={2}>
              <Button
                variant="primary"
                style={{ backgroundColor: "#0f3460", border: "none" }}
                onClick={handleApplyFilter}
              >
                Lọc
              </Button>
            </Col>
          </Row>
        </Container>

        <Container>
          {loading ? (
            <p>Loading...</p>
          ) : (
            <ShopList productItems={productsList} />
          )}

          {/* Pagination */}
          {totalPages > 1 && (
            <Pagination className="justify-content-center mt-4">
              {[...Array(totalPages)].map((_, i) => (
                <Pagination.Item
                  key={i + 1}
                  active={i + 1 === page}
                  onClick={() => handlePageChange(i + 1)}
                >
                  {i + 1}
                </Pagination.Item>
              ))}
            </Pagination>
          )}
        </Container>
      </section>
    </Fragment>
  );
};

export default Shop;
