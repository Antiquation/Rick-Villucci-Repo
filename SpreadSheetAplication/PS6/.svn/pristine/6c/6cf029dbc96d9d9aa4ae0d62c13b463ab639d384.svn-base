using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using SpreadsheetUtilities;
using System.Text.RegularExpressions;
using System.Xml;


namespace SS
{
    /// <summary>
    /// Spreadsheet Class
    /// </summary>
    public class Spreadsheet : AbstractSpreadsheet
    {

        //the collection of non duplicate cells located in the sheet
        private Dictionary<string, Cell> sheet;



        //the Dependency graph for the spreadSheet
        private DependencyGraph DG;

        //represents the current cell being manipulated
        private Cell current;

        //the filename
        public string fileName;

        //spreadsheet change indicator
        private bool Change = false;

        //variable holds the successfuly parsed string when needed by parseMe()
        private double Num;







        /// <summary>
        /// Zero argument constructor which allows the creation of 
        /// an AbstractSpreadsheet object. When a cell is visited it is instantiated with either
        /// a default (name + empty content) or if content is specified (name + valid content)
        /// it is then added to the Hashset.
        /// </summary>
        public Spreadsheet()
            : base(s => true, s => s, "default")
        {


            //the collection of non duplicate cells located in the sheet
            sheet = new Dictionary<string, Cell>();

            //creates the dependency graph associaed with the SpreadSheet
            DG = new DependencyGraph();


        }//end zero arg constructor


        public Spreadsheet(Func<string, bool> isValid, Func<string, string> normalize, string version)
            : base(isValid, normalize, version)
        {


            //the collection of non duplicate cells located in the sheet
            sheet = new Dictionary<string, Cell>();

            //creates the dependency graph associaed with the SpreadSheet
            DG = new DependencyGraph();



        }//end three arg constructor



        public Spreadsheet(string file, Func<string, bool> isValid, Func<string, string> normalize, string version)
            : base(isValid, normalize, version)
        {


            //the collection of non duplicate cells located in the sheet
            sheet = new Dictionary<string, Cell>();

            //creates the dependency graph associaed with the SpreadSheet
            DG = new DependencyGraph();

            //method for opening verifying and loading the file into memory
            readFile(file);



        }//end four arg constructor




        //end constructors**************************************************





        public override IEnumerable<string> GetNamesOfAllNonemptyCells()
        {
            //returns all the names of nonempty cells
            foreach (string s in sheet.Keys)
            {
                //brings the currently iterated cell local
                getCell(s);

                //if the cell content is not empty
                if (current.getContent() != "")
                    yield return s;
                else
                    continue;
            }//end iterator through spreadsheet

        }//end GetNames... method




        public override object GetCellContents(string name)
        {

            //check the validity of the name and that it is not null
            //else throw exception
            if (name == null || !Valid(name))
            {

                throw new InvalidNameException();
            }
            //grabs the cell and brings it local
            //then returns it's contents
            else
            {
                if (getCell(Normalize(name)))
                {
                    return current.getContent();
                }
                else
                {
                    //sets the cell
                    SetContentsOfCell(name, "");
                    //retrieves the cell to local   
                    getCell(name);

                    return current.getContent();

                }
            }

            //if the name is valid check that it exists


        }

        protected override ISet<string> SetCellContents(string name, double number)
        {

            //the local set to be returned
            HashSet<String> returnSet = new HashSet<String>();


            //if the name is null or invalid throw exception
            if (name == null || (!Valid(name)) || (!IsValid(name)))
                throw new InvalidNameException();


            //if the the name currently exist in the sheet continue...
            if (getCell(name))
            {

                //if the "current" cell contents are a formula
                //remove the dependencies so the new 
                //formula can be used...
                if (current.getContent() is Formula)
                {
                    //converts the object type to formula
                    Formula temp = (Formula)Convert.ChangeType(current.getContent(), typeof(Formula));

                    //remove the previous formulas dependencies from the DG
                    foreach (string s in temp.GetVariables())
                    {
                        DG.RemoveDependency(s, name);
                    }
                }//end remove the dependencies


                //replaces the cell content with the new number
                current.setCont(number);
                Changed = true;

            }//replaces contents in cell

            else
            {
                //adds the name and the new cell object containing the cell contents and its name to the sheet
                sheet.Add(name, new Cell(number, name));
                Changed = true;
            }



            //adds all the dependent cells to the local set
            //which will be returned from this method with the name of the current cell name
            foreach (string s in DG.GetDependents(name))
            {
                returnSet.Add(s);

            }//end foreach loop to add dependent cells to set

            //adds the name of the current cell to the set
            returnSet.Add(name);

            //recalculates all the affected cell values
            reCalc(returnSet);

            //retuns the cells dependencies set containing the name of itself + all its dependents
            return returnSet;


        }//end setCellContents - as number






        protected override ISet<string> SetCellContents(string name, string text)
        {

            //the local set to be returned
            HashSet<String> returnSet = new HashSet<String>();


            //if the name is null or invalid throw exception
            if (name == null || (!Valid(name)) || (!IsValid(name)))
                throw new InvalidNameException();


            if (text == null)
                throw new ArgumentNullException();


            //if the the name currently exist in the sheet continue...
            if (getCell(name))
            {

                //if the "current" cell contents are a formula
                //remove the dependencies so the new 
                //formula can be used...
                if (current.getContent() is Formula)
                {
                    //converts the object type to formula
                    Formula temp = (Formula)Convert.ChangeType(current.getContent(), typeof(Formula));

                    //remove the previous formulas dependencies from the DG
                    foreach (string s in temp.GetVariables())
                    {
                        DG.RemoveDependency(s, name);
                    }
                }//end remove the dependencies


                //replaces the cell content with the new text
                current.setCont(text);
                Changed = true;

            }//replaces contents in cell

            else
            {
                //adds the name and the new cell object containing the cell contents and its name to the sheet
                sheet.Add(name, new Cell(text, name));
                Changed = true;
            }



            //adds all the dependent cells to the local set
            //which will be returned from this method with the name of the current cell name
            foreach (string s in DG.GetDependents(name))
            {
                returnSet.Add(s);

            }//end foreach loop to add dependent cells to set

            //adds the name of the current cell to the set
            returnSet.Add(name);

            //recalculates all the affected cell values
            reCalc(returnSet);

            //retuns the cells dependencies set containing the name of itself + all its dependents
            return returnSet;

        }



        protected override ISet<string> SetCellContents(string name, Formula formula)//************
        {
            //the local set to be returned
            HashSet<String> returnSet = new HashSet<String>();


            //throw new NotImplementedException();
            //if the formula is null throw exception
            if (formula == null)
                throw new ArgumentException("The formula is non existent");

            //if the name is null or invalid throw exception
            if (name == null || (!Valid(name)) || (!IsValid(name)))
                throw new InvalidNameException();

            //if the the name currently exist in the sheet and the implimentation of the 
            //formula would cause circular dependency throw exception
            //else replace the content with the new formula and
            //regenerate the dependency graph
            if (getCell(name))
            {

                //if the formula presented is not the same as the current formula contained in 
                //the cell Verify the new formula is not circularly dependent
                if ((!current.getContent().Equals(formula)) && circular(name, formula))
                {
                    throw new CircularException();
                }
                //all is good continue
                else
                {
                    //if the "current" cell contents are a formula
                    //remove the dependencies so the new 
                    //formula can be used...
                    if (current.getContent() is Formula)
                    {
                        //converts the object type to formula
                        Formula temp = (Formula)Convert.ChangeType(current.getContent(), typeof(Formula));

                        //remove the previous formulas dependencies from the DG
                        foreach (string s in temp.GetVariables())
                        {
                            DG.RemoveDependency(s, name);
                        }
                    }//end remove the dependencies


                    //replaces the cell content with the new formula
                    current.setCont(formula);

                    //builds the dependency graph
                    addDep(formula, name);

                    Changed = true;

                }//replaces contents in cell


            }//end if formula exists and is not circular


                //the cell is non existent in the sheet and must be instantiated
            else
            {
                //if the formula would cause circular dependency
                //throw exception
                if (circular(name, formula))
                    throw new CircularException();

                    //if not circular add to the sheet and 
                //build the dependency graph for that name.
                else
                {
                    //adds the name and the new cell object containing the cell contents and its name to the sheet
                    sheet.Add(name, new Cell(formula, name));

                    //builds the dependency graph
                    addDep(formula, name);
                    Changed = true;


                }//end the cell is not circular dependent - build cell

            }//end else the cell is not instantiated



            //adds all the dependent cells to the local set
            //which will be returned from this method with the name of the current cell name
            foreach (string s in DG.GetDependents(name))
            {
                returnSet.Add(s);

            }//end foreach loop to add dependent cells to set

            //adds the name of the current cell to the set
            returnSet.Add(name);

            //recalculates all the affected cell values
            reCalc(returnSet);

            //retuns the cells dependencies set containing the name of itself + all its dependents
            return returnSet;


        }//end SetCellContents FORMULA




        protected override IEnumerable<string> GetDirectDependents(string name)//***********
        {
            //check the validity of the name and that it is not null
            //else throw exception
            if (name == null || (!Valid(name)))
            {
                //name is null
                if (name == null)
                    throw new ArgumentNullException();

                    //name is invalid
                else
                    throw new InvalidNameException();
            }//end if invalid throw exception

                //if all is good
            else
            {


                //returns the items in the dependency set internal to the cell
                foreach (string s in DG.GetDependents(name))
                {

                    //returns all the dependents of name
                    yield return s;

                }



            }//end else all is good - continue

        }//end GetDirectDependents method




        /// <summary>
        /// gets the version info from the file passed into the system
        /// </summary>
        /// <param name="filename">string - file name</param>
        /// <returns>string - Version</returns>
        public override string GetSavedVersion(string filename)
        {
            try
            {

                XmlReader reader;

                    //local version whitch will be returned
                        string version = null;

                    try
                    {
                        //using the xmlreader
                        reader = XmlReader.Create(filename);
                    }
                     catch(System.IO.FileNotFoundException)
                    {
                         throw new SpreadsheetReadWriteException("file not fould");
                    }

                            using (reader)

                   
                                 

                                //read the version info from xml file
                                while (version == null)
                                {
                                    if (reader.IsStartElement())
                                    {
                                        switch (reader.Name)
                                        {
                                            case "spreadsheet":
                                                version = reader["version"];

                                                break;
                                        }
                                    }

                                }
                            //return the version
                            return version.ToUpper();
                    
            }
           
            catch
            {

                //if it couldn't return a value or something went wrong throw exception
                throw new SpreadsheetReadWriteException("Error interpreting file version");

            }
        }//end getversion



        public override void Save(string filename)
        {
            fileName = filename;
            
            //counter
            int i = 0;
            string F = null;

            try
            {       //using the xml creater
                using (XmlWriter writer = XmlWriter.Create(filename))
                {
                    //checked that the spreadsheed is not null
                    if (sheet != null)
                    {
                        //opens the doument
                        writer.WriteStartDocument();
                        //sets the spreadsheet element
                        writer.WriteStartElement("spreadsheet");
                        //save the Version Information
                        writer.WriteAttributeString("version", Version.ToString());

                        //iterator
                        while (i < sheet.Keys.Count)
                        {
                            //gets the cell at the current index location
                            getCell(sheet.Keys.ElementAt(i));

                            //Start Cell Node
                            writer.WriteStartElement("cell");

                            //Start the Cell Name node and add the current name of the cell
                            writer.WriteElementString("name", current.getName());

                            //If the cell contents is a formula
                            if (current.getContent() is Formula)
                            {       //adds the "=" sign
                                F = null;
                                F += '=';
                                //loads the formula in string form on top
                                F += current.getContent().ToString();
                                //writes the node
                                writer.WriteElementString("contents", F);
                            }

                                //if the content is anything other than a formula load node
                            else
                            {
                                writer.WriteElementString("contents", current.getContent().ToString());
                            }

                            //End Cell Node
                            writer.WriteEndElement();

                            //End of document reached Close out Spreadsheet node and Document
                            if (i == (sheet.Keys.Count - 1))
                            {
                                //End SpreadSheet
                                writer.WriteEndElement();
                                writer.WriteEndDocument();
                            }

                            i++;//incriment to the next cell

                        }//end While spreadsheet has cells

                    }//end if statment

                else
                {
                    throw new FormulaFormatException("Error");
                }


                }//end using writer

               





            }//end Try writing document

            catch
            {

                throw new SpreadsheetReadWriteException("Error saving file");
            }
        }//end Save Method



        /// <summary>
        /// Gets the specified cells value and returns it / or a FormulaError
        /// </summary>
        /// <param name="name">string name of cell</param>
        /// <returns>object - double, string, or FormulaError</returns>
        public override object GetCellValue(string name)
        {

            //check the validity of the name and that it is not null
            //else throw exception
            if (name == null || !Valid(name))
            {
                throw new InvalidNameException();
            }

            else if (!IsValid(name))
                throw new FormulaFormatException("Invalid Variable Format");

            //grabs the cell and brings it local
            //then returns it's contents
            else
            {
                //if the cell is non existent
                if (!getCell(name))
                {
                    //sets the cell
                    SetContentsOfCell(name, "");
                    //retrieves the cell to local   
                    getCell(name);

                    return current.getValue();

                }

                    //returns the value if cell
                else
                {
                    return current.getValue();
                }


            }//end return value
        }


        /// <summary>
        /// Sets the contents of the cells initially when the file is read
        /// and loaded into the spreadsheet
        /// </summary>
        /// <param name="name"></param>
        /// <param name="content"></param>
        /// <returns></returns>
        public override ISet<string> SetContentsOfCell(string name, string content)
        {



            //if content is null throw exception
            if (content == null)
            {
                throw new ArgumentNullException();
            }
            //if name is null or invalid throw exception
            else if (name == null || (!Valid(name)) || (!IsValid(name)))
            {
                throw new InvalidNameException();
            }

            else
            {

                name = Normalize(name);
            }


            //if the content is a string representation of a number
            //then the cell will be built as a number
            if (parseMe(content))
            {
                //adds the name and the new cell object containing the cell contents and its name to the sheet
                return SetCellContents(name, Num);

            }


               //if the string is a representation of a formula
            else if (content.Length > 0 && content[0] == '=')
            {
                //formula string
                string toPass = "";

                //iterate through the string and remove the '='
                foreach (char s in content)
                {
                    if (s == '=')
                        continue;
                    else
                    {

                        toPass += s;
                    }

                   
                }


               
                            //If the formula contains invalid components Throw error
                    foreach (string T in new Formula(Normalize(toPass)).GetVariables())
                    {

                        //if name is null or invalid throw exception
                        if (T == null || (!Valid(T)) || (!IsValid(T)))
                        {
                            throw new FormulaFormatException("Invalid Formula");
                        }
                    }


                    //try and set the content of the cell
                try
                {
                    return SetCellContents(name, new Formula(Normalize(toPass)));


                }

                    //if the new formula is not valid
                catch
                {
                    //Returns the appropriate error if it exists
                }

            }//end if the string is a formula



            //the contents of the string are a string
            return SetCellContents(name, content);




        }//end setContentsOfCell() method




        //*************************HELPER METHODS****************

        /// <summary>
        /// Helper Method...
        /// Given a cells name - this method will determine if it had an illegal
        /// circular dependency and returns true if circular and false if not.
        /// </summary>
        /// <param name="cellName">String</param>
        /// <param name="formula">Formula</param>
        /// <returns>boolean</returns>
        private bool circular(string cellName, Formula formula)//**********
        {
            foreach (string v in formula.GetVariables())
            {
                //gets all the cells dependents and if 
                //the cell name appears in that iteration 
                //then the cell had circular dependency - return true
                foreach (string s in DG.GetDependents(v))
                {
                    if (s.Equals(v))
                    {
                        return true;
                    }
                }
                //if the cellName has a formula which is circular return true 
                if (v.Equals(cellName))
                    return true;


            }


            //Cell is legal - return false
            return false;
        }//end circular method





        /// <summary>
        /// Helper Method...
        /// Gets the Cell curently under observation - if it exists - and stores it localy for analasys.
        /// returns true if the cell name is in the sheet else returns false
        /// </summary>
        /// <param name="s">string - Name of cell</param>
        /// <returns>bool</returns>
        private bool getCell(string s)
        {
            return sheet.TryGetValue(s, out current);
        }




        /// <summary>
        /// Takes the name of the cell and the 
        /// fomula contained therein - gets all variables and
        /// adds the cell name as directly dependant on all the variables in the formula
        /// </summary>
        /// <param name="F">Formula - graph "key" or all the dependees</param>
        /// <param name="name">string - graph "value" or dependents of the formula it is dependant on the formula</param
        private void addDep(Formula F, string name)//*************
        {
            //iterates through the formula and builds the 
            //dependency graph
            foreach (string s in F.GetVariables())
            {
                //creates all the dependents for the Cell "name" passed in 
                DG.AddDependency(s, name);

                //if the name is not in the spreadsheet add it
                if (!(getCell(s)))
                {
                    SetCellContents(s, "");
                }
            }

        }//end addDep method


        /// <summary>
        /// Checks for default name validation
        /// </summary>
        /// <param name="s">cell name</param>
        /// <returns>bool</returns>
        private bool Valid(string s)
        {

            if (((Regex.IsMatch(s, @"^[a-zA-Z]+\d+$"))))
                return true;

            return false;

        }


        /// <summary>
        /// The default delegate method passed into the system
        /// used for the zero argument constructor
        /// This method returns whatever it takes in
        /// </summary>
        /// <param name="name">string - Name of the cell</param>
        /// <returns>string - Name of the cell - NO CHANGE TO THE STRING</returns>
        public static string defConst(string name)
        {
            string pass = null;
            pass = name;
            return pass;
        }


        /// <summary>
        /// Helper method for 4 part constructor ment to control the incoming xml file interpretation of a spreadsheet
        /// and to validate said spreadsheet cells while loading into the system
        /// </summary>
        /// <param name="file">string - the file name</param>
        /// <param name="version">string - the version of the incoming file</param>
        public void readFile(string file)
        {
            fileName = file;
            //sends the file name to GetSavedVersion to check that the versions match
            if (!(GetSavedVersion(file).Equals(base.Version.ToUpper())))
            {
                //if versions don't match throw error
                throw new VersionMismatchException("Saved spreadsheet and specified spreadsheet versions do not match");

            }//end if versions match


            //if the versions match then proceed reading the xml file
            try
            {

                //Accesses the xml file using the xmlreader
                using (XmlReader reader = XmlReader.Create(file))
                {
                    string name = null;
                    string content = null;

                    //while the xml file has a node
                    while (reader.Read())
                    {


                        if (reader.IsStartElement())
                        {

                            switch (reader.Name)
                            {

                                case "spreadsheet":
                                    break;

                                case "cell":
                                    break;

                                //if the node is the cell name it takes the name validates it iterates to the cell contents node in the 
                                //XML file and sends the whole package to the setContentsOfCell() method for additional validity checks and 
                                //eventual addition to the spreadsheet
                                case "name":
                                    name = null;
                                    //reads the contents of "name"
                                    reader.Read();
                                    //sets that content to the local "name"
                                    name = reader.Value;
                                    //Normalizes the name based on user delegate
                                    Normalize(name);
                                    //Check if the name passes the delegated IsValid parameter
                                    if (!Valid(name))
                                    {
                                        throw new InvalidNameException();
                                    }
                                    break;

                                case "contents":
                                    content = null;
                                    //iterates to the next element which is the cell content
                                    reader.Read();
                                    //reads the contents of "contents"
                                    content = reader.Value;


                                    //passes all the info to the Set
                                    SetContentsOfCell(name, content);
                                    Changed = false;

                                    break;





                            }//end switch

                        }//end if is start element

                    }//end Wile loop


                }//end using xml reader

            }//end try block

            catch
            {
                //Catch All
                throw new SpreadsheetReadWriteException("Error reading XML file");

            }//end catch all



        }//end readFile helper method.


        /// <summary>
        /// Helper method for setting the value of each cell once changed.
        /// </summary>
        /// <param name="names">ISet of type String - All the cells to be recalculated in the order of dependency</param>
        private void reCalc(ISet<String> names)
        {

            //calculate and save the value of each cell in the dependency chain
            foreach (string s in GetCellsToRecalculate(names))
            {
                getCell(s);

                if (!(current.getContent() is Formula))
                {
                    current.setValue(current.getContent());
                }

                else
                {
                    //converts the object type to formula
                    Formula temp = (Formula)Convert.ChangeType(current.getContent(), typeof(Formula));
                    //calculates the current value of the cell and saves it into the cells value
                    current.setValue(temp.Evaluate(verGet));

                }

            }
        }

        /// <summary>
        /// Helper for reCalc to be passed into the formula as the lookup delegate
        /// if the value is a double returns that value else throws an ArgumentException
        /// </summary>
        /// <param name="s">string - Name of cell</param>
        /// <returns>double - value of current cell</returns>
        private double verGet(string s)
        {
            //converts the object type to formula

            double temp;
            try
            {
                getCell(s);
                temp = (Double)Convert.ChangeType(current.getValue(), typeof(Double));
                return temp;
            }
            catch
            {
                if (!getCell(s))
                    SetCellContents(s, "");
                throw new ArgumentException();
            }






        }//end Delgate passed method


        /// <summary>
        /// Takes a string representation of a number and returns true or false if it was successfull
        /// Then if successful loads the value into the Num variable for access by the preogram
        /// </summary>
        /// <param name="s">String representation of a potential</param>
        /// <returns>True or False</returns>
        private bool parseMe(string s)
        {
            return Double.TryParse(s, out Num);
        }


        public override bool Changed
        {
            get;
            protected set;
        }
    }//end Spreadsheed class




    /// <summary>
    /// Version Exception thrown if the versions are mismatched.
    /// </summary>
    public class VersionMismatchException : Exception
    {
        /// <summary>
        /// Creates the exception with a message
        /// </summary>
        public VersionMismatchException(string msg)
            : base(msg)
        {
        }

    }


}//End SS namespace
