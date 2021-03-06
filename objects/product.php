<?php
    class Product {
        private $connection; 
        private $table_name = 'products';

        private $id;
        private $name;
        private $description;
        private $price;
        private $category_id;
        private $category_name;
        private $created;

        public function __construct($db){
            $this->connection = $db;
        }

        public function read()
        {
            $query = 'SELECT c.name as category_name, p.id, p.name, p.description, p.price, p.category_id, p.created 
                    From ' . $this->table_name . ' p
                    LEFT JOIN categories c
                    ON p.category_id = c.id
                    ORDER BY p.created DESC';

            $stmt = $this->connection->prepare($query);
            $stmt->execute();
            
            return $stmt;
        }

        public function create(){
            $query = 'INSERT INTO ' . $this->table_name . 'SET name=:name, price=:price, description=:description, categpry_id=:category_id, created=:created';
            $stmt = $this->connection->prepare($query);

            // cleaning
            $this->name = htmlspecialchars(strip_tags($this->name));
            $this->price = htmlspecialchars(strip_tags($this->price));
            $this->description = htmlspecialchars(strip_tags($this->description));
            $this->category_id = htmlspecialchars(strip_tags($this->category_id));
            $this->created = htmlspecialchars(strip_tags($this->created));

            // bind params
            $stmt->bindParam(':name', $this->name);
            $stmt->bindParam(':price', $this->price);
            $stmt->bindParam(':description', $this->description);
            $stmt->bindParam(':category_id', $this->category_id);
            $stmt->bindParam(':created', $this->created);

            if($stmt->execute()){
                return true;
            }

            return false;
        }

        public function readOne(){
            $query = 'SELECT c.name as category_name, p.id, p.name, p.description, p.price, p.category_id, p.created 
                    From ' . $this->table_name . ' p
                    LEFT JOIN categories c
                    ON p.category_id = c.id
                    WHERE p.id = ? 
                    LIMIT 0,1';
            
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(1, $this->id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $this->name = $row['name'];        
            $this->price = $row['price'];        
            $this->description = $row['description'];        
            $this->category_id = $row['category_id'];        
            $this->category_name = $row['category_name'];        
        }

        public function update(){
            $query = 'UPDATE ' . $this->table_name . '
                    SET name = :name, price = :price, description = :description, category_id = :category_id
                    WHERE id = :id';

            $stmt = $this->connection->prepare($query);

            // cleaning
            $this->name = htmlspecialchars(strip_tags($this->name)); 
            $this->price = htmlspecialchars(strip_tags($this->price)); 
            $this->description = htmlspecialchars(strip_tags($this->description)); 
            $this->category_id = htmlspecialchars(strip_tags($this->category_id)); 
            $this->id = htmlspecialchars(strip_tags($this->id));
            
            // bind params
            $stmt->bindParam(':name', $this->name);
            $stmt->bindParam(':price', $this->price);
            $stmt->bindParam(':description', $this->description);
            $stmt->bindParam(':category_id', $this->category_id);
            $stmt->bindParam(':id', $this->id);

            if($stmt->execute()){
                return true;
            }

            return false;
        }

        public function delete(){
            $query = 'DELETE FROM ' . $this->table_name . ' WHERE id = ?';
            $stmt = $this->connection->prepare($query);
            
            // cleaning
            $this->id = htmlspecialchars(strip_tags($this->id));
            
            // bind params
            $stmt->bindParam(1, $this->id);

            if($stmt->execute()){
                return true;
            }

            return false;
        }

        public function search(){
            $query = 'SELECT c.name as category_name, p.id, p.name, p.description, p.price, p.category_id, p.created 
                    From ' . $this->table_name . ' p
                    LEFT JOIN categories c
                    ON p.category_id = c.id
                    WHERE p.name LIKE ? OR p.description LIKE ? OR c.name LIKE ?
                    ORDER BY p.created DESC';
            
            $stmt = $this->connection->prepare($query);
            $keywords = htmlspecialchars(strip_tags($keywords));
            $keywords = '%{$keywords}%';

            // bind params
            $stmt->bindParam(1, $keywords);
            $stmt->bindParam(2, $keywords);
            $stmt->bindParam(3, $keywords);
            $stmt->execute();
            return $stmt;
        }

        public function readPaging($from_record_num, $record_per_page){
            $query = 'SELECT c.name as category_name, p.id, p.name, p.description, p.price, p.category_id, p.created 
            From ' . $this->table_name . ' p
            LEFT JOIN categories c
            ON p.category_id = c.id
            ORDER BY p.created DESC
            LIMIT ?, ?';

            $stmt = $this->connection->prepare($query);
            
            // bind params
            $stmt->bindParam(1, $from_record_num, PDO::PARAM_INT);
            $stmt->bindParam(2, $record_per_page, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt;
        }

        public function count(){
            $query = 'SELECT COUNT(*) as total_rows FROM ' . $this->table_name . '';

            $stmt = $this->connection->prepare($query);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            return $row['total_rows'];
        }
    }
?>