import mysql.connector
import pandas as pd
from mlxtend.frequent_patterns import apriori, association_rules
import json
import sys
# import matplotlib.pyplot as plt  # Importing Matplotlib
# import seaborn as sns

def main():
    try:
        # Database connection details
        config = {
            'user': 'root',
            'password': '',  # Leave it as an empty string if your password is empty
            'host': 'localhost',
            'database': 'browlesque'
        }

        # Establish a database connection using a context manager
        with mysql.connector.connect(**config) as connection:
            # Query to fetch the transaction data
            query = "SELECT appointment_id, service_id FROM client_appointment"
            # Load the data into a DataFrame
            data = pd.read_sql(query, connection)

            # Query to fetch the mapping of service IDs to service names
            service_mapping_query = "SELECT service_id, service_name FROM services"
            # Load the service mapping data into a DataFrame
            service_mapping = pd.read_sql(service_mapping_query, connection)

        # Filter out rows where service_id is empty or NaN
        data = data.dropna(subset=['service_id'])
        data = data[data['service_id'].str.strip() != '']

        # Clean the service_id strings
        data['service_id'] = data['service_id'].str.replace(r'[\[\]"]', '', regex=True).str.split(',')

        # Explode the list of service IDs into individual rows
        data = data.explode('service_id')

        # Convert service_id to integer
        data['service_id'] = data['service_id'].astype(int)

        # Merge the original data with the service mapping data to get service names
        data_with_names = pd.merge(data, service_mapping, on='service_id')

        # Transform the data into a basket format
        basket = data_with_names.groupby(['appointment_id', 'service_name'])['service_name'].count().unstack().reset_index().fillna(0).set_index('appointment_id')
        basket = basket.apply(lambda x: x > 0)  # Use boolean values

        # Print the query to check if it's correct
        # print("Service Mapping Query:")
        # print(service_mapping_query)

        # Print the result of the service mapping query
        # print("Service Mapping Result:")
        # print(service_mapping)

        # Print data_with_names to check if the merge operation is successful
        # print("Data with Names:")
        # print(data_with_names)

        # Print basket to check if it's empty
        # print("Basket:")
        # print(basket)

        # Generate frequent itemsets with a lower min_support
        frequent_itemsets = apriori(basket, min_support=0.5, use_colnames=True)

        # Print frequent_itemsets to check if it's empty
        # print("Frequent Itemsets:")
        # print(frequent_itemsets.head())

        # Print the data DataFrame
        # print("Data DataFrame:")
        # print(data)

        # Print the query used to fetch data
        # print("Query Used:")
        # print(query)

        # Generate association rules with a lower min_threshold
        rules = association_rules(frequent_itemsets, metric="lift", min_threshold=0.5)

        # Ensure 'lift' column is numeric
        rules['lift'] = pd.to_numeric(rules['lift'], errors='coerce')  # Convert 'lift' column to numeric

        # Drop rows with NaN values in the 'lift' column
        rules = rules.dropna(subset=['lift'])

        # Convert antecedents and consequents to readable strings
        rules['antecedents'] = rules['antecedents'].apply(lambda x: ', '.join(list(x)))
        rules['consequents'] = rules['consequents'].apply(lambda x: ', '.join(list(x)))

        # Prepare a column for display labels combining antecedents and consequents
        rules['rule'] = rules['antecedents'] + " => " + rules['consequents']

        # Print the rules DataFrame to check if it's empty
        print("Association Rules:")
        print(rules)

        # Visualize the top 10 association rules based on lift
        top_rules = rules.nlargest(10, 'lift')

        # Print top_rules to check if it's empty
        # print("Top 10 Association Rules by Lift:")
        print(top_rules)

        # Plotting the top rules
        # plt.figure(figsize=(12, 8))
        # sns.barplot(x='lift', y='rule', data=top_rules, palette='viridis', hue='rule', dodge=False)
        # plt.title('Top 10 Association Rules by Lift')
        # plt.xlabel('Lift')
        # plt.ylabel('Association Rules')
        # plt.xticks(rotation=90)
        # plt.show()

        # Store results back into MySQL
        # Re-establish the database connection using a context manager
        with mysql.connector.connect(**config) as connection:
            # Create a new DataFrame to store the rules in a suitable format
            rules_df = rules[['antecedents', 'consequents', 'support', 'confidence', 'lift', 'conviction']].copy()

            # Insert the rules into the association_rules table
            with connection.cursor() as cursor:
                # Ensure the table exists with a unique constraint
                cursor.execute("""
                    CREATE TABLE IF NOT EXISTS association_rules (
                        antecedents VARCHAR(255),
                        consequents VARCHAR(255),
                        support FLOAT,
                        confidence FLOAT,
                        lift FLOAT,
                        conviction FLOAT,
                        UNIQUE KEY unique_rule (antecedents, consequents)
                    )
                """)

                cursor.execute("TRUNCATE TABLE association_rules")
                
                for _, row in rules_df.iterrows():
                    cursor.execute("""
                        INSERT INTO association_rules (antecedents, consequents, support, confidence, lift, conviction)
                        VALUES (%s, %s, %s, %s, %s, %s)
                        ON DUPLICATE KEY UPDATE
                            support = VALUES(support),
                            confidence = VALUES(confidence),
                            lift = VALUES(lift),
                            conviction = VALUES(conviction)
                    """, tuple(row))

            connection.commit()
        
         # Output the JSON response
        print(json.dumps({"status": "success", "message": "Python script executed successfully"}))
    
    except Exception as e:
        # If there was an error, print the error message
        print(json.dumps({"status": "error", "message": str(e)}), file=sys.stderr)

if __name__ == "__main__":
    main()